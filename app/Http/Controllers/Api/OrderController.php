<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Material;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Project;
use App\Models\ProjectItem;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index() {
        $orders = Order::where('user_id', auth()->id())
            ->with('project')
            ->latest()
            ->get();

        return response()->json($orders);
    }

    public function show(Order $order) {
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json(
            $order->load(['items.material', 'items.projectItem', 'project'])
        );
    }

    public function store(Request $request) {
        $data = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'order_type' => ['nullable', Rule::in(['partial', 'full'])],
            'delivery_method' => ['nullable', Rule::in(['pickup', 'delivery'])],
            'delivery_address' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.project_item_id' => ['required', 'integer', 'exists:project_items,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        $project = Project::where('id', $data['project_id'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$project) {
            return response()->json([
                'message' => 'Project not found/unauthorized'
            ], 404);
        }

        $deliveryMethod = $data['delivery_method'] ?? 'pickup';

        if ($deliveryMethod === 'delivery' && empty($data['delivery_address'])) {
            return response()->json([
                'message' => 'delivery_address wajib diisi jika delivery_method = delivery'
            ], 422);
        }

        $orderType = $data['order_type'] ?? 'partial';

        $requestedItems = collect($data['items']);
        $projectItemIds = $requestedItems->pluck('project_item_id')->all();

        if (count($projectItemIds) !== count(array_unique($projectItemIds))) {
            return response()->json([
                'message' => 'project_item_id tidak boleh duplikat dalam satu order'
            ], 422);
        }

        $projectItems = ProjectItem::with('material')
            ->where('project_id', $project->id)
            ->whereIn('id', $projectItemIds)
            ->get()
            ->keyBy('id');

        if ($projectItems->count() !== count($projectItemIds)) {
            return response()->json([
                'message' => 'Ada project_item yang tidak valid atau tidak sesuai project'
            ], 422);
        }

        foreach ($data['items'] as $requestedItem) {
            $projectItemId = (int) $requestedItem['project_item_id'];
            $qtyRequested = (int) $requestedItem['qty'];

            $pi = $projectItems->get($projectItemId);

            if (!$pi) {
                return response()->json([
                    'message' => "Project item ID {$projectItemId} tidak ditemukan"
                ], 422);
            }

            $remaining = max(0, (int) $pi->qty_needed - (int) $pi->qty_purchased);

            if ($remaining <= 0) {
                return response()->json([
                    'message' => "Item '{$this->resolveItemName($pi)}' sudah terpenuhi, tidak bisa dipesan lagi"
                ], 422);
            }

            if ($orderType === 'full') {
                if ($qtyRequested !== $remaining) {
                    return response()->json([
                        'message' => "Untuk order_type full, qty item '{$this->resolveItemName($pi)}' harus sama dengan sisa kebutuhan ({$remaining})"
                    ], 422);
                }
            } else {
                if ($qtyRequested > $remaining) {
                    return response()->json([
                        'message' => "Qty item '{$this->resolveItemName($pi)}' melebihi sisa kebutuhan ({$remaining})"
                    ], 422);
                }
            }
        }

        return DB::transaction(function () use ($project, $projectItems, $data, $deliveryMethod, $orderType) {
            $orderCode = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

            $order = Order::create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'order_code' => $orderCode,
                'order_type' => $orderType,
                'status' => Order::STATUS_DRAFT,
                'delivery_method' => $deliveryMethod,
                'delivery_address' => $deliveryMethod === 'delivery'
                    ? ($data['delivery_address'] ?? null)
                    : null,
                'subtotal' => 0,
                'shipping_fee' => 0,
                'total_amount' => 0,
            ]);

            $subtotal = 0.0;

            foreach ($data['items'] as $requestedItem) {
                $projectItemId = (int) $requestedItem['project_item_id'];
                $qtyRequested = (int) $requestedItem['qty'];

                $pi = $projectItems->get($projectItemId);
                $nameSnapshot = $this->resolveItemName($pi);

                $unitPrice = $pi->material?->price_estimate;
                $unitPrice = $unitPrice !== null ? (float) $unitPrice : 0.0;

                $lineTotal = $unitPrice * $qtyRequested;
                $subtotal += $lineTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'project_item_id' => $pi->id,
                    'material_id' => $pi->material_id,
                    'name_snapshot' => $nameSnapshot,
                    'qty' => $qtyRequested,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ]);
            }

            $order->update([
                'subtotal' => $subtotal,
                'total_amount' => $subtotal,
            ]);

            return response()->json(
                $order->load(['items.material', 'items.projectItem', 'project']),
                201
            );
        });
    }

    public function submit(Order $order) {
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($order->status !== Order::STATUS_DRAFT) {
            return response()->json([
                'message' => 'Order bukan status draft'
            ], 422);
        }

        if ($order->items()->count() === 0) {
            return response()->json([
                'message' => 'Order tidak memiliki item'
            ], 422);
        }

        $order->update([
            'status' => Order::STATUS_WAITING_ADMIN
        ]);

        return response()->json([
            'message' => 'Order submitted',
            'order' => $order->fresh()->load(['items.material', 'items.projectItem', 'project'])
        ]);
    }

    public function adminAdjust(Request $request, Order $order) {
        if ($order->status !== Order::STATUS_WAITING_ADMIN) {
            return response()->json([
                'message' => 'Order hanya bisa direvisi saat status waiting_admin'
            ], 422);
        }

        $data = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.order_item_id' => ['required', 'integer', 'exists:order_items,id'],
            'items.*.qty' => ['required', 'integer', 'min:0'],
            'items.*.unit_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        return DB::transaction(function () use ($data, $order) {
            $lockedOrder = Order::whereKey($order->id)
                ->lockForUpdate()
                ->first();

            $orderItems = $lockedOrder->items()
                ->with('projectItem.material')
                ->get()
                ->keyBy('id');

            $remainingActiveItems = $orderItems->count();

            foreach ($data['items'] as $payload) {
                $orderItemId = (int) $payload['order_item_id'];
                $qty = (int) $payload['qty'];

                $orderItem = $orderItems->get($orderItemId);

                if (!$orderItem) {
                    return response()->json([
                        'message' => "Order item ID {$orderItemId} tidak ditemukan di order ini"
                    ], 422);
                }

                $projectItem = ProjectItem::whereKey($orderItem->project_item_id)
                    ->lockForUpdate()
                    ->first();

                if (!$projectItem) {
                    return response()->json([
                        'message' => "Project item untuk order item ID {$orderItemId} tidak ditemukan"
                    ], 422);
                }

                if ($qty === 0) {
                    $remainingActiveItems--;
                    continue;
                }

                $needed = (int) $projectItem->qty_needed;
                $purchased = (int) $projectItem->qty_purchased;

                $reservedByOthers = $this->getReservedQtyForProjectItem(
                    $projectItem->id,
                    $lockedOrder->id
                );

                $availableToReserve = max(0, $needed - $purchased - $reservedByOthers);

                if ($qty > $availableToReserve) {
                    return response()->json([
                        'message' => "Qty untuk item '{$orderItem->name_snapshot}' melebihi sisa kebutuhan yang tersedia untuk dibooking ({$availableToReserve})"
                    ], 422);
                }
            }

            if ($remainingActiveItems <= 0) {
                return response()->json([
                    'message' => 'Order tidak boleh kosong. Sisakan minimal 1 item.'
                ], 422);
            }

            foreach ($data['items'] as $payload) {
                $orderItemId = (int) $payload['order_item_id'];
                $qty = (int) $payload['qty'];

                $orderItem = $orderItems->get($orderItemId);

                if ($qty === 0) {
                    $orderItem->delete();
                    continue;
                }

                $unitPrice = array_key_exists('unit_price', $payload)
                    ? (float) $payload['unit_price']
                    : (float) $orderItem->unit_price;

                $lineTotal = $qty * $unitPrice;

                $orderItem->update([
                    'qty' => $qty,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ]);
            }

            $newSubtotal = (float) $lockedOrder->items()->sum('line_total');
            $shippingFee = (float) ($lockedOrder->shipping_fee ?? 0);
            $newTotalAmount = $newSubtotal + $shippingFee;

            $lockedOrder->update([
                'subtotal' => $newSubtotal,
                'total_amount' => $newTotalAmount,
            ]);

            return response()->json([
                'message' => 'Order berhasil direvisi',
                'order' => $lockedOrder->fresh()->load(['items.material', 'items.projectItem', 'project']),
            ]);
        });
    }

    public function setWaitingPayment(Request $request, Order $order) {
        $data = $request->validate([
            'shipping_fee' => ['nullable', 'numeric', 'min:0'],
        ]);

        if ($order->status !== Order::STATUS_WAITING_ADMIN) {
            return response()->json([
                'message' => 'Order harus status waiting_admin'
            ], 422);
        }

        if ($order->items()->count() === 0) {
            return response()->json([
                'message' => 'Order tidak memiliki item'
            ], 422);
        }

        return DB::transaction(function () use ($data, $order) {
            $lockedOrder = Order::whereKey($order->id)
                ->lockForUpdate()
                ->first();

            $lockedOrder->load(['items.projectItem.material']);

            foreach ($lockedOrder->items as $item) {
                $projectItem = ProjectItem::whereKey($item->project_item_id)
                    ->lockForUpdate()
                    ->first();

                if (!$projectItem) {
                    return response()->json([
                        'message' => "Project item untuk '{$item->name_snapshot}' tidak ditemukan"
                    ], 422);
                }

                $needed = (int) $projectItem->qty_needed;
                $purchased = (int) $projectItem->qty_purchased;

                $reservedByOthers = OrderItem::query()
                    ->where('project_item_id', $projectItem->id)
                    ->where('order_id', '!=', $lockedOrder->id)
                    ->whereHas('order', function ($q) {
                        $q->whereIn('status', [
                            Order::STATUS_WAITING_PAYMENT,
                            Order::STATUS_PAID,
                            Order::STATUS_PROCESSING,
                            Order::STATUS_SHIPPED,
                            Order::STATUS_READY_PICKUP,
                        ]);
                    })
                    ->sum('qty');

                $availableToReserve = max(0, $needed - $purchased - (int) $reservedByOthers);
                $thisOrderQty = (int) $item->qty;

                if ($thisOrderQty > $availableToReserve) {
                    return response()->json([
                        'message' => "Qty item '{$item->name_snapshot}' melebihi sisa kebutuhan yang tersedia untuk dibooking ({$availableToReserve})"
                    ], 422);
                }
            }

            $shippingFee = (float) ($data['shipping_fee'] ?? 0);
            $totalAmount = (float) $lockedOrder->subtotal + $shippingFee;

            $lockedOrder->update([
                'shipping_fee' => $shippingFee,
                'total_amount' => $totalAmount,
                'status' => Order::STATUS_WAITING_PAYMENT,
            ]);

            return response()->json([
                'message' => 'Order set to waiting_payment',
                'order' => $lockedOrder->fresh()->load(['items.material', 'items.projectItem', 'project'])
            ]);
        });
    }

    public function markPaid(Order $order) {
        if ($order->status !== Order::STATUS_WAITING_PAYMENT) {
            return response()->json([
                'message' => 'Order belum waiting_payment'
            ], 422);
        }

        $order->update([
            'status' => Order::STATUS_PAID
        ]);

        return response()->json([
            'message' => 'Order paid',
            'order' => $order->fresh()->load(['items.material', 'items.projectItem', 'project'])
        ]);
    }

    public function process(Order $order) {
        if ($order->status !== Order::STATUS_PAID) {
            return response()->json([
                'message' => 'Order harus status paid untuk masuk ke processing'
            ], 422);
        }

        $order->update([
            'status' => Order::STATUS_PROCESSING,
        ]);

        return response()->json([
            'message' => 'Order moved to processing',
            'order' => $order->fresh()->load(['items.material', 'items.projectItem', 'project'])
        ]);
    }

    public function ship(Order $order) {
        if ($order->status !== Order::STATUS_PROCESSING) {
            return response()->json([
                'message' => 'Order harus status processing untuk dikirim'
            ], 422);
        }

        if ($order->delivery_method !== 'delivery') {
            return response()->json([
                'message' => 'Hanya order delivery yang bisa diubah ke shipped'
            ], 422);
        }

        $order->update([
            'status' => Order::STATUS_SHIPPED,
        ]);

        return response()->json([
            'message' => 'Order shipped',
            'order' => $order->fresh()->load(['items.material', 'items.projectItem', 'project'])
        ]);
    }

    public function readyPickup(Order $order)
{
        if ($order->status !== Order::STATUS_PROCESSING) {
            return response()->json([
                'message' => 'Order harus status processing untuk siap pickup'
            ], 422);
        }

        if ($order->delivery_method !== 'pickup') {
            return response()->json([
                'message' => 'Hanya order pickup yang bisa diubah ke ready_pickup'
            ], 422);
        }

        $order->update([
            'status' => Order::STATUS_READY_PICKUP,
        ]);

        return response()->json([
            'message' => 'Order ready for pickup',
            'order' => $order->fresh()->load(['items.material', 'items.projectItem', 'project'])
        ]);
    }

    public function complete(Order $order)
    {
        if (!in_array($order->status, [Order::STATUS_SHIPPED, Order::STATUS_READY_PICKUP])) {
            return response()->json([
                'message' => 'Order harus status shipped atau ready_pickup untuk diselesaikan'
            ], 422);
        }

        return DB::transaction(function () use ($order) {
            if (!$order->is_applied_to_project) {
                $this->applyOrderItemsToProject($order);
            }

            $order->update([
                'status' => Order::STATUS_COMPLETED,
            ]);

            return response()->json([
                'message' => 'Order completed',
                'order' => $order->fresh()->load(['items.material', 'items.projectItem', 'project'])
            ]);
        });
    }

    public function cancel(Order $order)
    {
        if (!in_array($order->status, [
            Order::STATUS_DRAFT,
            Order::STATUS_WAITING_ADMIN,
            Order::STATUS_WAITING_PAYMENT,
        ])) {
            return response()->json([
                'message' => 'Order hanya bisa dibatalkan saat status draft, waiting_admin, atau waiting_payment'
            ], 422);
        }

        $order->update([
            'status' => Order::STATUS_CANCELLED,
        ]);

        return response()->json([
            'message' => 'Order cancelled',
            'order' => $order->fresh()->load(['items.material', 'items.projectItem', 'project'])
        ]);
    }

    public function adminIndex(Request $request) {
        $request->validate([
            'status' => ['nullable', Rule::in([
                Order::STATUS_DRAFT,
                Order::STATUS_WAITING_ADMIN,
                Order::STATUS_WAITING_PAYMENT,
                Order::STATUS_PAID,
                Order::STATUS_PROCESSING,
                Order::STATUS_SHIPPED,
                Order::STATUS_READY_PICKUP,
                Order::STATUS_COMPLETED,
                Order::STATUS_CANCELLED,
            ])],
            'delivery_method' => ['nullable', Rule::in(['pickup', 'delivery'])],
            'order_code' => ['nullable', 'string'],
        ]);

        $query = Order::with(['project', 'user'])->withCount('items')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('delivery_method')) {
            $query->where('delivery_method', $request->delivery_method);
        }

        if ($request->filled('order_code')) {
            $query->where('order_code', 'like', '%' . $request->order_code . '%');
        }

        $orders = $query->paginate(10);

        return response()->json($orders);
    }

    public function adminShow(Order $order) {
        return response()->json(
            $order->load(['items.material', 'items.projectItem', 'project', 'user'])
        );
    }

    public function adminDashboard() {
        $data = [
            'waiting_admin' => Order::where('status', Order::STATUS_WAITING_ADMIN)->count(),
            'waiting_payment' => Order::where('status', Order::STATUS_WAITING_PAYMENT)->count(),
            'paid' => Order::where('status', Order::STATUS_PAID)->count(),
            'processing' => Order::where('status', Order::STATUS_PROCESSING)->count(),
            'shipped' => Order::where('status', Order::STATUS_SHIPPED)->count(),
            'ready_pickup' => Order::where('status', Order::STATUS_READY_PICKUP)->count(),
            'completed' => Order::where('status', Order::STATUS_COMPLETED)->count(),
            'cancelled' => Order::where('status', Order::STATUS_CANCELLED)->count(),
            'total_orders' => Order::count(),
            'total_revenue_completed' => (float) Order::where('status', Order::STATUS_COMPLETED)->sum('total_amount'),

            'total_active_projects' => Project::where('status', Project::STATUS_ACTIVE)->count(),
            'total_completed_projects' => Project::where('status', Project::STATUS_COMPLETED)->count(),

            'total_contractors' => User::where('role', 'contractor')->count(),

            'recent_orders' => Order::with(['project', 'user'])
                ->latest()
                ->take(5)
                ->get(),

            'top_materials' => Material::select('materials.id', 'materials.name', 'materials.variant')
                ->join('order_items', 'order_items.material_id', '=', 'materials.id')
                ->selectRaw('SUM(order_items.qty) as total_ordered_qty')
                ->groupBy('materials.id', 'materials.name', 'materials.variant')
                ->orderByDesc('total_ordered_qty')
                ->take(5)
                ->get(),
        ];

        return response()->json($data);
    }

    public function createPaymentToken(Order $order) {
        if ($order->status !== Order::STATUS_WAITING_PAYMENT) {
            return response()->json([
                'message' => 'Order harus status waiting_payment untuk membuat payment token'
            ], 422);
        }

        if ($order->total_amount <= 0) {
            return response()->json([
                'message' => 'Total amount tidak valid'
            ], 422);
        }

        $order->loadMissing(['user', 'items']);
        $this->initMidtransConfig();

        $customer = $order->user;

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_code,
                'gross_amount' => (int) round($order->total_amount),
            ],
            'customer_details' => [
                'first_name' => $customer->name ?? 'Customer',
                'email' => $customer->email ?? 'customer@example.com',
                'phone' => $customer->phone ?? '',
            ],
            'item_details' => $order->items->map(function ($item) {
                return [
                    'id' => (string) $item->id,
                    'price' => (int) round($item->unit_price),
                    'quantity' => (int) $item->qty,
                    'name' => $item->name_snapshot,
                ];
            })->values()->all(),
            'callbacks' => [
                'finish' => config('midtrans.finish_url'),
                'unfinish' => config('midtrans.unfinish_url'),
                'error' => config('midtrans.error_url'),
            ],
        ];

        if ((float) $order->shipping_fee > 0) {
            $params['item_details'][] = [
                'id' => 'shipping',
                'price' => (int) round($order->shipping_fee),
                'quantity' => 1,
                'name' => 'Shipping Fee',
            ];
        }

        try {
            $transaction = Snap::createTransaction($params);

            $order->update([
                'payment_token' => $transaction->token,
                'payment_url' => $transaction->redirect_url,
                'transaction_status' => 'token_created',
            ]);

            return response()->json([
                'message' => 'Payment token created',
                'order' => $order->fresh()->load(['items.material', 'items.projectItem', 'project']),
                'snap_token' => $transaction->token,
                'payment_url' => $transaction->redirect_url,
                'client_key' => config('midtrans.client_key'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal membuat payment token',
                'error' => $e->getMessage(),
                'server_key_exists' => !empty(config('midtrans.server_key')),
                'server_key_prefix' => substr((string) config('midtrans.server_key'), 0, 13),
                'client_key_prefix' => substr((string) config('midtrans.client_key'), 0, 13),
                'is_production' => config('midtrans.is_production'),
            ], 500);
        }
    }

    public function midtransWebhook(Request $request) {
        $this->initMidtransConfig();

        try {
            $notification = new Notification();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Invalid Midtrans notification',
                'error' => $e->getMessage(),
            ], 400);
        }

        $transactionStatus = $notification->transaction_status ?? null;
        $paymentType = $notification->payment_type ?? null;
        $fraudStatus = $notification->fraud_status ?? null;
        $orderCode = $notification->order_id ?? null;

        $order = Order::where('order_code', $orderCode)->first();

        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        $expectedSignature = hash(
            'sha512',
            $orderCode .
            $notification->status_code .
            $notification->gross_amount .
            trim((string) config('midtrans.server_key'))
        );

        if (($notification->signature_key ?? null) !== $expectedSignature) {
            return response()->json([
                'message' => 'Invalid signature'
            ], 403);
        }

        $updatePayload = [
            'payment_type' => $paymentType,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
        ];

        if ($transactionStatus === 'capture') {
            if ($paymentType === 'credit_card') {
                if ($fraudStatus === 'accept') {
                    $updatePayload['status'] = Order::STATUS_PAID;
                    $updatePayload['paid_at'] = now();
                } elseif ($fraudStatus === 'challenge') {
                    $updatePayload['status'] = Order::STATUS_WAITING_PAYMENT;
                }
            }
        } elseif ($transactionStatus === 'settlement') {
            $updatePayload['status'] = Order::STATUS_PAID;
            $updatePayload['paid_at'] = now();
        } elseif ($transactionStatus === 'pending') {
            $updatePayload['status'] = Order::STATUS_WAITING_PAYMENT;
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $updatePayload['status'] = Order::STATUS_WAITING_PAYMENT;
        }   

        if ( in_array($transactionStatus, ['capture', 'settlement']) &&
            $order->status === Order::STATUS_PAID &&
            $order->paid_at) {
            return response()->json([
                'message' => 'Order already processed as paid'
            ]);
        }

        $order->update($updatePayload);

        return response()->json([
            'message' => 'Notification processed'
        ]);
    }

    private function applyOrderItemsToProject(Order $order): void {
        $orderItems = $order->items()->with('projectItem')->get();

        foreach ($orderItems as $orderItem) {
            $projectItem = ProjectItem::whereKey($orderItem->project_item_id)
                ->lockForUpdate()
                ->first();

            if (!$projectItem) {
                throw new \Exception("Project item untuk '{$orderItem->name_snapshot}' tidak ditemukan");
            }

            $currentPurchased = (int) $projectItem->qty_purchased;
            $qtyToApply = (int) $orderItem->qty;
            $needed = (int) $projectItem->qty_needed;

            $remaining = max(0, $needed - $currentPurchased);

            if ($qtyToApply > $remaining) {
                throw new \Exception(
                    "Qty item '{$orderItem->name_snapshot}' melebihi sisa kebutuhan saat complete ({$remaining})"
                );
            }

            $newPurchased = $currentPurchased + $qtyToApply;
            $newStatus = $this->calculateProjectItemStatus($needed, $newPurchased);

            $projectItem->update([
                'qty_purchased' => $newPurchased,
                'status' => $newStatus,
            ]);
        }

        $order->update([
            'is_applied_to_project' => true,
            'applied_to_project_at' => now(),
        ]);

        if ($order->project) {
            $this->refreshProjectStatus($order->project);
        }
    }

    private function getReservedStatuses(): array {
        return [
            Order::STATUS_WAITING_PAYMENT,
            Order::STATUS_PAID,
            Order::STATUS_PROCESSING,
            Order::STATUS_SHIPPED,
            Order::STATUS_READY_PICKUP,
        ];
    }

    private function getReservedQtyForProjectItem(int $projectItemId, ?int $excludeOrderId = null): int {
        $query = OrderItem::query()
            ->where('project_item_id', $projectItemId)
            ->whereHas('order', function ($q) {
                $q->whereIn('status', $this->getReservedStatuses());
            });

        if ($excludeOrderId) {
            $query->where('order_id', '!=', $excludeOrderId);
        }

        return (int) $query->sum('qty');
    }

    private function validateOrderCanBeReserved(Order $order): ?array {
        $order->loadMissing(['items.projectItem.material']);

        foreach ($order->items as $orderItem) {
            $projectItem = $orderItem->projectItem;

            if (!$projectItem) {
                return [
                    'ok' => false,
                    'message' => "Project item untuk '{$orderItem->name_snapshot}' tidak ditemukan"
                ];
            }

            $needed = (int) $projectItem->qty_needed;
            $purchased = (int) $projectItem->qty_purchased;

            $reservedByOthers = $this->getReservedQtyForProjectItem(
                $projectItem->id,
                $order->id
            );

            $availableToReserve = max(0, $needed - $purchased - $reservedByOthers);
            $thisOrderQty = (int) $orderItem->qty;

            if ($thisOrderQty > $availableToReserve) {
                return [
                    'ok' => false,
                    'message' => "Qty item '{$orderItem->name_snapshot}' melebihi sisa kebutuhan yang tersedia untuk dibooking ({$availableToReserve})"
                ];
            }
        }

        return null;
    }

    private function refreshProjectStatus(Project $project): void {
        $items = $project->items()->get();

        if ($items->isEmpty()) {
            $project->update([
                'status' => Project::STATUS_DRAFT,
            ]);
            return;
        }

        $allCompleted = $items->every(function ($item) {
            return in_array($item->status, [
                ProjectItem::STATUS_COMPLETE,
                ProjectItem::STATUS_SUBSTITUTED,
            ]);
        });

        $project->update([
            'status' => $allCompleted
                ? Project::STATUS_COMPLETED
                : Project::STATUS_ACTIVE,
        ]);
    }

    private function calculateProjectItemStatus(int $needed, int $purchased): string {
        if ($purchased <= 0) {
            return ProjectItem::STATUS_NOT_BOUGHT;
        }

        if ($purchased >= $needed) {
            return ProjectItem::STATUS_COMPLETE;
        }

        return ProjectItem::STATUS_PARTIAL;
    }

    private function resolveItemName(ProjectItem $projectItem): string {
        if ($projectItem->material) {
            return trim(collect([
                $projectItem->material->name ?? null,
                $projectItem->material->variant ?? null,
            ])->filter()->implode(' '));
        }

        return $projectItem->custom_name ?? 'Custom Item';
    }

    private function initMidtransConfig(): void {
        Config::$serverKey = trim((string) config('midtrans.server_key'));
        Config::$clientKey = trim((string) config('midtrans.client_key'));
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        Config::$is3ds = (bool) config('midtrans.is_3ds');
    }

}