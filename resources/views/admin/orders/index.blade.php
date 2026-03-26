@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Orders</h1>

    <form method="GET" class="bg-white rounded-xl shadow p-4 mb-4 flex flex-col md:flex-row gap-3">
        <input type="text" name="order_code" value="{{ request('order_code') }}" placeholder="Cari order code..." class="border rounded-lg px-4 py-2">
        
        <select name="status" class="border rounded-lg px-4 py-2">
            <option value="">Semua Status</option>
            @foreach(['draft','waiting_admin','waiting_payment','paid','processing','shipped','ready_pickup','completed','cancelled'] as $status)
                <option value="{{ $status }}" @selected(request('status') == $status)>{{ $status }}</option>
            @endforeach
        </select>

        <button class="bg-slate-900 text-white px-4 py-2 rounded-lg">Filter</button>
    </form>

    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b">
                    <th class="px-4 py-3">Order Code</th>
                    <th class="px-4 py-3">User</th>
                    <th class="px-4 py-3">Project</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Delivery</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="border-b">
                        <td class="px-4 py-3">{{ $order->order_code }}</td>
                        <td class="px-4 py-3">{{ $order->user->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $order->project->project_name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $order->status }}</td>
                        <td class="px-4 py-3">{{ $order->delivery_method }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:underline">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-4 text-center text-gray-500">Belum ada order.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
@endsection