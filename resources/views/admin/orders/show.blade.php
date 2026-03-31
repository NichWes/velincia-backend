@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Detail Order</h1>
            <p class="text-sm text-gray-500 mt-1">Lihat detail, review, dan proses order.</p>
        </div>

        <a href="{{ route('admin.orders.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
            Kembali ke Orders
        </a>
    </div>
    <h1 class="text-2xl font-bold mb-6">Detail Order</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow p-5">
                <h2 class="text-lg font-semibold mb-4">Informasi Order</h2>

                @php
                    $statusClasses = match($order->status) {
                        'draft' => 'bg-gray-100 text-gray-700',
                        'waiting_admin' => 'bg-yellow-100 text-yellow-800',
                        'waiting_payment' => 'bg-orange-100 text-orange-800',
                        'paid' => 'bg-emerald-100 text-emerald-800',
                        'processing' => 'bg-blue-100 text-blue-800',
                        'shipped' => 'bg-indigo-100 text-indigo-800',
                        'ready_pickup' => 'bg-purple-100 text-purple-800',
                        'completed' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                        default => 'bg-gray-100 text-gray-700',
                    };
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    <div><strong>Order Code:</strong> {{ $order->order_code }}</div>
                    <strong>Status:</strong><span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClasses }}">{{ $order->status }}</span>
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
                        <form method="POST" action="{{ route('admin.orders.setWaitingPayment', $order) }}" class="space-y-3"
                            onsubmit="return confirm('Ubah order ke waiting_payment?')">
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
                        <form method="POST" action="{{ route('admin.orders.process', $order) }}"
                            onsubmit="return confirm('Ubah status order ke processing?')">
                            @csrf
                            <button class="w-full bg-blue-600 text-white py-2 rounded-lg">
                                Set Processing
                            </button>
                        </form>
                    @endif

                    @if($order->status === 'processing' && $order->delivery_method === 'delivery')
                        <form method="POST" action="{{ route('admin.orders.process', $order) }}"
                            onsubmit="return confirm('Ubah status order ke processing?')">
                            @csrf
                            <button class="w-full bg-blue-600 text-white py-2 rounded-lg">
                                Set Processing
                            </button>
                        </form>
                    @endif

                    @if($order->status === 'processing' && $order->delivery_method === 'pickup')
                        <form method="POST" action="{{ route('admin.orders.readyPickup', $order) }}"
                            onsubmit="return confirm('Ubah status order ke ready pickup?')">
                            @csrf
                            <button class="w-full bg-purple-600 text-white py-2 rounded-lg">
                                Set Ready Pickup
                            </button>
                        </form>
                    @endif

                    @if(in_array($order->status, ['shipped', 'ready_pickup']))
                        <form method="POST" action="{{ route('admin.orders.readyPickup', $order) }}"
                            onsubmit="return confirm('Ubah status order ke ready pickup?')">
                            @csrf
                            <button class="w-full bg-purple-600 text-white py-2 rounded-lg">
                                Set Ready Pickup
                            </button>
                        </form>
                    @endif

                    @if(in_array($order->status, ['draft', 'waiting_admin', 'waiting_payment']))
                        <form method="POST" action="{{ route('admin.orders.cancel', $order) }}"
                            onsubmit="return confirm('Batalkan order ini?')">
                            @csrf
                            <button class="w-full bg-red-600 text-white py-2 rounded-lg">
                                Cancel Order
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection