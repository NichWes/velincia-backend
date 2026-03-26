@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Total Orders</p>
            <p class="text-2xl font-bold">{{ $totalOrders }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Pending Orders</p>
            <p class="text-2xl font-bold">{{ $pendingOrders }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Completed Orders</p>
            <p class="text-2xl font-bold">{{ $completedOrders }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Revenue</p>
            <p class="text-2xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-5">
        <h2 class="text-lg font-semibold mb-4">Recent Orders</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b">
                        <th class="py-2">Order Code</th>
                        <th class="py-2">User</th>
                        <th class="py-2">Project</th>
                        <th class="py-2">Status</th>
                        <th class="py-2">Total</th>
                        <th class="py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr class="border-b">
                            <td class="py-3">{{ $order->order_code }}</td>
                            <td class="py-3">{{ $order->user->name ?? '-' }}</td>
                            <td class="py-3">{{ $order->project->project_name ?? '-' }}</td>
                            <td class="py-3">{{ $order->status }}</td>
                            <td class="py-3">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="py-3">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:underline">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-gray-500">Belum ada order.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection