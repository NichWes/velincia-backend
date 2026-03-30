@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Detail Order</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow p-5">
                <h2 class="text-lg font-semibold mb-4">Informasi Order</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    <div><strong>Order Code:</strong> {{ $order->order_code }}</div>
                    <div><strong>Status:</strong> {{ $order->status }}</div>
                    <div><strong>User:</strong> {{ $order->user->name ?? '-' }}</div>
                    <div><strong>Project:</strong> {{ $order->project->title ?? '-' }}</div>
                    <div><strong>Delivery:</strong> {{ $order->delivery_method }}</div>
                    <div><strong>Total:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-5">
                <h2 class="text-lg font-semibold mb-4">Order Items</h2>

                @if($order->status === 'waiting_admin')
                    <form method="POST" action="{{ route('admin.orders.adjust', $order) }}">
                        @csrf

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b text-left">
                                        <th class="py-2">Nama Item</th>
                                        <th class="py-2">Qty</th>
                                        <th class="py-2">Unit Price</th>
                                        <th class="py-2">Subtotal</th>
                                        <th class="py-2">Project Item Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($order->items as $index => $item)
                                        <tr class="border-b">
                                            <td class="py-3">
                                                {{ $item->material_name ?? $item->projectItem->custom_name ?? 'Item' }}

                                                <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                            </td>
                                            <td class="py-3">
                                                <input
                                                    type="number"
                                                    name="items[{{ $index }}][qty]"
                                                    value="{{ old("items.$index.qty", $item->qty) }}"
                                                    min="1"
                                                    class="w-24 border rounded-lg px-3 py-2"
                                                    required
                                                >
                                            </td>
                                            <td class="py-3">
                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    min="0"
                                                    name="items[{{ $index }}][unit_price]"
                                                    value="{{ old("items.$index.unit_price", $item->unit_price) }}"
                                                    class="w-32 border rounded-lg px-3 py-2"
                                                >
                                            </td>
                                            <td class="py-3">
                                                Rp {{ number_format($item->subtotal ?? ($item->qty * ($item->unit_price ?? 0)), 0, ',', '.') }}
                                            </td>
                                            <td class="py-3">
                                                {{ $item->projectItem->status ?? '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-4 text-center text-gray-500">
                                                Tidak ada item order.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($order->items->count())
                            <div class="mt-4">
                                <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded-lg">
                                    Simpan Adjust Order
                                </button>
                            </div>
                        @endif
                    </form>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b text-left">
                                    <th class="py-2">Nama Item</th>
                                    <th class="py-2">Qty</th>
                                    <th class="py-2">Unit Price</th>
                                    <th class="py-2">Subtotal</th>
                                    <th class="py-2">Project Item Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->items as $item)
                                    <tr class="border-b">
                                        <td class="py-3">
                                            {{ $item->material_name ?? $item->projectItem->custom_name ?? 'Item' }}
                                        </td>
                                        <td class="py-3">{{ $item->qty }}</td>
                                        <td class="py-3">Rp {{ number_format($item->unit_price ?? 0, 0, ',', '.') }}</td>
                                        <td class="py-3">Rp {{ number_format($item->subtotal ?? ($item->qty * ($item->unit_price ?? 0)), 0, ',', '.') }}</td>
                                        <td class="py-3">{{ $item->projectItem->status ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 text-center text-gray-500">
                                            Tidak ada item order.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow p-5">
                <h2 class="text-lg font-semibold mb-4">Aksi Admin</h2>
                <div class="space-y-3">
                    @if($order->status === 'waiting_admin')
                        <form method="POST" action="{{ route('admin.orders.setWaitingPayment', $order) }}" class="space-y-3">
                            @csrf

                            <div>
                                <label class="block text-sm font-medium mb-1">Shipping Fee</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    name="shipping_fee"
                                    value="{{ old('shipping_fee', $order->shipping_fee ?? 0) }}"
                                    class="w-full border rounded-lg px-4 py-2"
                                >
                            </div>

                            <button class="w-full bg-amber-500 text-white py-2 rounded-lg">
                                Set Waiting Payment
                            </button>
                        </form>
                    @endif

                    @if($order->status === 'paid')
                        <form method="POST" action="{{ route('admin.orders.process', $order) }}">
                            @csrf
                            <button class="w-full bg-blue-600 text-white py-2 rounded-lg">Set Processing</button>
                        </form>
                    @endif

                    @if($order->status === 'processing' && $order->delivery_method === 'delivery')
                        <form method="POST" action="{{ route('admin.orders.ship', $order) }}">
                            @csrf
                            <button class="w-full bg-indigo-600 text-white py-2 rounded-lg">Set Shipped</button>
                        </form>
                    @endif

                    @if($order->status === 'processing' && $order->delivery_method === 'pickup')
                        <form method="POST" action="{{ route('admin.orders.readyPickup', $order) }}">
                            @csrf
                            <button class="w-full bg-purple-600 text-white py-2 rounded-lg">Set Ready Pickup</button>
                        </form>
                    @endif

                    @if(in_array($order->status, ['shipped', 'ready_pickup']))
                        <form method="POST" action="{{ route('admin.orders.complete', $order) }}">
                            @csrf
                            <button class="w-full bg-green-600 text-white py-2 rounded-lg">Complete Order</button>
                        </form>
                    @endif

                    @if(in_array($order->status, ['draft', 'waiting_admin', 'waiting_payment']))
                        <form method="POST" action="{{ route('admin.orders.cancel', $order) }}">
                            @csrf
                            <button class="w-full bg-red-600 text-white py-2 rounded-lg">Cancel Order</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection