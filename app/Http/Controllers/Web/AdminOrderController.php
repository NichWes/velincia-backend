<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller {
    public function index(Request $request) {
        $query = Order::with(['user', 'project'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('order_code')) {
            $query->where('order_code', 'like', '%' . $request->order_code . '%');
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order) {
        $order->load(['user', 'project', 'items.projectItem', 'items']);

        return view('admin.orders.show', compact('order'));
    }

    public function adjust(Request $request, Order $order) {
        if ($order->status !== 'waiting_admin') {
            return back()->with('error', 'Order hanya bisa di-adjust saat status waiting_admin.');
        }

        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer', 'exists:order_items,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($validated, $order) {
            $subtotal = 0;

            foreach ($validated['items'] as $itemData) {
                $orderItem = $order->items()->where('id', $itemData['id'])->first();

                if (!$orderItem) {
                    continue;
                }

                $qty = (int) $itemData['qty'];
                $unitPrice = isset($itemData['unit_price']) ? (float) $itemData['unit_price'] : (float) ($orderItem->unit_price ?? 0);
                $itemSubtotal = $qty * $unitPrice;

                $orderItem->update([
                    'qty' => $qty,
                    'unit_price' => $unitPrice,
                    'subtotal' => $itemSubtotal,
                ]);

                $subtotal += $itemSubtotal;
            }

            $shippingFee = (float) ($order->shipping_fee ?? 0);

            $order->update([
                'subtotal' => $subtotal,
                'total_amount' => $subtotal + $shippingFee,
            ]);
        });

        return back()->with('success', 'Order berhasil di-adjust.');
    }

    public function setWaitingPayment(Request $request, Order $order) {
        if ($order->status !== 'waiting_admin') {
            return back()->with('error', 'Status order harus waiting_admin sebelum diubah ke waiting_payment.');
        }

        $validated = $request->validate([
            'shipping_fee' => ['nullable', 'numeric', 'min:0'],
        ]);

        $shippingFee = isset($validated['shipping_fee']) ? (float) $validated['shipping_fee'] : 0;
        $subtotal = (float) ($order->subtotal ?? 0);

        $order->update([
            'shipping_fee' => $shippingFee,
            'total_amount' => $subtotal + $shippingFee,
            'status' => 'waiting_payment',
        ]);

        return back()->with('success', 'Order berhasil diubah ke waiting_payment.');
    }

    public function process(Order $order) {
        if ($order->status !== 'paid') {
            return back()->with('error', 'Order harus status paid sebelum diproses.');
        }

        $order->update(['status' => 'processing']);

        return back()->with('success', 'Order berhasil diubah ke processing.');
    }

    public function ship(Order $order) {
        if ($order->status !== 'processing') {
            return back()->with('error', 'Order harus processing sebelum shipped.');
        }

        if ($order->delivery_method !== 'delivery') {
            return back()->with('error', 'Order ini bukan delivery.');
        }

        $order->update(['status' => 'shipped']);

        return back()->with('success', 'Order berhasil diubah ke shipped.');
    }

    public function readyPickup(Order $order) {
        if ($order->status !== 'processing') {
            return back()->with('error', 'Order harus processing sebelum ready pickup.');
        }

        if ($order->delivery_method !== 'pickup') {
            return back()->with('error', 'Order ini bukan pickup.');
        }

        $order->update(['status' => 'ready_pickup']);

        return back()->with('success', 'Order berhasil diubah ke ready pickup.');
    }

    public function complete(Order $order) {
        if (!in_array($order->status, ['shipped', 'ready_pickup'])) {
            return back()->with('error', 'Order harus shipped atau ready_pickup sebelum complete.');
        }

        if (!$order->is_applied_to_project) {
            foreach ($order->items as $item) {
                if ($item->projectItem) {
                    $projectItem = $item->projectItem;
                    $newPurchased = min(
                        $projectItem->qty_needed,
                        $projectItem->qty_purchased + $item->qty
                    );

                    $status = 'partial';
                    if ($newPurchased <= 0) {
                        $status = 'not_bought';
                    } elseif ($newPurchased >= $projectItem->qty_needed) {
                        $status = 'complete';
                    }

                    $projectItem->update([
                        'qty_purchased' => $newPurchased,
                        'status' => $status,
                    ]);
                }
            }

            $order->update([
                'is_applied_to_project' => true,
                'applied_to_project_at' => now(),
                'status' => 'completed',
            ]);
        } else {
            $order->update([
                'status' => 'completed',
            ]);
        }

        return back()->with('success', 'Order berhasil diselesaikan.');
    }

    public function cancel(Order $order) {
        if (!in_array($order->status, ['draft', 'waiting_admin', 'waiting_payment'])) {
            return back()->with('error', 'Order tidak bisa dibatalkan dari status ini.');
        }

        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Order berhasil dibatalkan.');
    }
}