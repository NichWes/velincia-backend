<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Project;
use App\Models\ProjectItem;
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
                'shipping_fee' => null,
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

        $orderItems = $order->items()
            ->with('projectItem')
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

            $projectItem = $orderItem->projectItem;

            if (!$projectItem) {
                return response()->json([
                    'message' => "Project item untuk order item ID {$orderItemId} tidak ditemukan"
                ], 422);
            }

            $remaining = max(
                0,
                (int) $projectItem->qty_needed - (int) $projectItem->qty_purchased
            );

            if ($qty > 0 && $qty > $remaining) {
                return response()->json([
                    'message' => "Qty untuk item '{$orderItem->name_snapshot}' melebihi sisa kebutuhan ({$remaining})"
                ], 422);
            }

            if ($qty === 0) {
                $remainingActiveItems--;
            }
        }

        if ($remainingActiveItems <= 0) {
            return response()->json([
                'message' => 'Order tidak boleh kosong. Sisakan minimal 1 item.'
            ], 422);
        }

        return DB::transaction(function () use ($data, $order, $orderItems) {
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

            $newSubtotal = (float) $order->items()->sum('line_total');
            $shippingFee = (float) ($order->shipping_fee ?? 0);
            $newTotalAmount = $newSubtotal + $shippingFee;

            $order->update([
                'subtotal' => $newSubtotal,
                'total_amount' => $newTotalAmount,
            ]);

            return response()->json([
                'message' => 'Order berhasil direvisi',
                'order' => $order->fresh()->load(['items.material', 'items.projectItem', 'project']),
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

        $shippingFee = (float) ($data['shipping_fee'] ?? 0);
        $totalAmount = (float) $order->subtotal + $shippingFee;

        $order->update([
            'shipping_fee' => $shippingFee,
            'total_amount' => $totalAmount,
            'status' => Order::STATUS_WAITING_PAYMENT,
        ]);

        return response()->json([
            'message' => 'Order set to waiting_payment',
            'order' => $order->fresh()->load(['items.material', 'items.projectItem', 'project'])
        ]);
    }

    public function markPaid(Order $order) {
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

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

    public function applyToProjectItems(Order $order) {
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        if (!in_array($order->status, [Order::STATUS_PAID, Order::STATUS_PROCESSING])) {
            return response()->json([
                'message' => 'Order harus status paid atau processing'
            ], 422);
        }

        if ($order->is_applied_to_project) {
            return response()->json([
                'message' => 'Order ini sudah pernah diaplikasikan ke project items'
            ], 422);
        }

        if ($order->items()->count() === 0) {
            return response()->json([
                'message' => 'Order tidak memiliki item'
            ], 422);
        }

        return DB::transaction(function () use ($order) {
            $orderItems = $order->items()->with('projectItem')->get();

            foreach ($orderItems as $orderItem) {
                $projectItem = $orderItem->projectItem;

                if (!$projectItem) {
                    continue;
                }

                $currentPurchased = (int) $projectItem->qty_purchased;
                $qtyToApply = (int) $orderItem->qty;
                $needed = (int) $projectItem->qty_needed;

                $newPurchased = $currentPurchased + $qtyToApply;

                if ($newPurchased > $needed) {
                    $newPurchased = $needed;
                }

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

            return response()->json([
                'message' => 'Order berhasil diaplikasikan ke project items',
                'order' => $order->fresh()->load(['items.projectItem', 'project']),
            ]);
        });
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
            return trim(
                ($projectItem->material->name ?? '') . ' ' . ($projectItem->material->variant ?? '')
            );
        }

        return $projectItem->custom_name ?? 'Custom Item';
    }
}