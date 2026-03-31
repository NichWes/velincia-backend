@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Orders</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola order contractor dan proses statusnya.</p>
        </div>
    </div>

    <form method="GET" class="bg-white rounded-xl shadow p-4 mb-4 grid grid-cols-1 md:grid-cols-4 gap-3">
        <div>
            <label class="block text-sm font-medium mb-1">Order Code</label>
            <input
                type="text"
                name="order_code"
                value="{{ request('order_code') }}"
                placeholder="Cari order code..."
                class="w-full border rounded-lg px-4 py-2"
            >
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Status</label>
            <select name="status" class="w-full border rounded-lg px-4 py-2">
                <option value="">Semua Status</option>
                @foreach(['draft','waiting_admin','waiting_payment','paid','processing','shipped','ready_pickup','completed','cancelled'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>
                        {{ $status }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Delivery Method</label>
            <select name="delivery_method" class="w-full border rounded-lg px-4 py-2">
                <option value="">Semua</option>
                <option value="pickup" @selected(request('delivery_method') === 'pickup')>pickup</option>
                <option value="delivery" @selected(request('delivery_method') === 'delivery')>delivery</option>
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded-lg">
                Filter
            </button>

            <a href="{{ route('admin.orders.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
                Reset
            </a>
        </div>
    </form>

    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b bg-gray-50">
                    <th class="px-4 py-3">Order Code</th>
                    <th class="px-4 py-3">User</th>
                    <th class="px-4 py-3">Project</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Delivery</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Created</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
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

                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">{{ $order->order_code }}</td>
                        <td class="px-4 py-3">{{ $order->user->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $order->project->title ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClasses }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 capitalize">{{ $order->delivery_method }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">{{ $order->created_at?->format('d M Y H:i') ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:underline font-medium">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-10 text-center text-gray-500">
                            Tidak ada order yang cocok dengan filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
@endsection