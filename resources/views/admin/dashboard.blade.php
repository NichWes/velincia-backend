@extends('layouts.admin')

@section('content')
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">
                Ringkasan operasional order dan material Velincia HPL.
            </p>
        </div>

        <form method="GET" class="flex flex-wrap gap-2">
            <a href="{{ route('admin.dashboard', ['range' => 'today']) }}"
               class="px-4 py-2 rounded-lg text-sm {{ $range === 'today' ? 'bg-slate-900 text-white' : 'bg-white border text-gray-700' }}">
                Hari Ini
            </a>

            <a href="{{ route('admin.dashboard', ['range' => '7d']) }}"
               class="px-4 py-2 rounded-lg text-sm {{ $range === '7d' ? 'bg-slate-900 text-white' : 'bg-white border text-gray-700' }}">
                7 Hari
            </a>

            <a href="{{ route('admin.dashboard', ['range' => '30d']) }}"
               class="px-4 py-2 rounded-lg text-sm {{ $range === '30d' ? 'bg-slate-900 text-white' : 'bg-white border text-gray-700' }}">
                30 Hari
            </a>

            <a href="{{ route('admin.dashboard', ['range' => 'all']) }}"
               class="px-4 py-2 rounded-lg text-sm {{ $range === 'all' ? 'bg-slate-900 text-white' : 'bg-white border text-gray-700' }}">
                Semua
            </a>
        </form>
    </div>

    {{-- Summary utama --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow p-5">
            <p class="text-sm text-gray-500">Total Orders</p>
            <p class="text-3xl font-bold mt-2">{{ $totalOrders }}</p>
            <p class="text-xs text-gray-400 mt-2">Sesuai filter periode terpilih</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-5">
            <p class="text-sm text-gray-500">Pending Orders</p>
            <p class="text-3xl font-bold mt-2">{{ $pendingOrders }}</p>
            <p class="text-xs text-gray-400 mt-2">waiting_admin, waiting_payment, paid, processing</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-5">
            <p class="text-sm text-gray-500">Revenue Selesai</p>
            <p class="text-3xl font-bold mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-2">Hanya order completed</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-5">
            <p class="text-sm text-gray-500">Potential Revenue</p>
            <p class="text-3xl font-bold mt-2">Rp {{ number_format($potentialRevenue, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-2">Order aktif yang belum selesai</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow p-5">
            <p class="text-sm text-gray-500">Avg Completed Order</p>
            <p class="text-2xl font-bold mt-2">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-5">
            <p class="text-sm text-gray-500">Completion Rate</p>
            <p class="text-2xl font-bold mt-2">{{ number_format($completionRate, 1) }}%</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-5">
            <p class="text-sm text-gray-500">Cancellation Rate</p>
            <p class="text-2xl font-bold mt-2">{{ number_format($cancellationRate, 1) }}%</p>
        </div>
    </div>

    {{-- Status order --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Status Order</h2>
                <span class="text-sm text-gray-400">Distribusi status saat ini</span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                <div class="rounded-xl bg-gray-50 p-4">
                    <p class="text-xs text-gray-500">Draft</p>
                    <p class="text-2xl font-bold mt-1">{{ $draftOrders }}</p>
                </div>

                <div class="rounded-xl bg-yellow-50 p-4">
                    <p class="text-xs text-yellow-700">Waiting Admin</p>
                    <p class="text-2xl font-bold mt-1">{{ $waitingAdminOrders }}</p>
                </div>

                <div class="rounded-xl bg-orange-50 p-4">
                    <p class="text-xs text-orange-700">Waiting Payment</p>
                    <p class="text-2xl font-bold mt-1">{{ $waitingPaymentOrders }}</p>
                </div>

                <div class="rounded-xl bg-emerald-50 p-4">
                    <p class="text-xs text-emerald-700">Paid</p>
                    <p class="text-2xl font-bold mt-1">{{ $paidOrders }}</p>
                </div>

                <div class="rounded-xl bg-blue-50 p-4">
                    <p class="text-xs text-blue-700">Processing</p>
                    <p class="text-2xl font-bold mt-1">{{ $processingOrders }}</p>
                </div>

                <div class="rounded-xl bg-indigo-50 p-4">
                    <p class="text-xs text-indigo-700">Shipped</p>
                    <p class="text-2xl font-bold mt-1">{{ $shippedOrders }}</p>
                </div>

                <div class="rounded-xl bg-purple-50 p-4">
                    <p class="text-xs text-purple-700">Ready Pickup</p>
                    <p class="text-2xl font-bold mt-1">{{ $readyPickupOrders }}</p>
                </div>

                <div class="rounded-xl bg-green-50 p-4">
                    <p class="text-xs text-green-700">Completed</p>
                    <p class="text-2xl font-bold mt-1">{{ $completedOrders }}</p>
                </div>

                <div class="rounded-xl bg-red-50 p-4 md:col-span-2 xl:col-span-1">
                    <p class="text-xs text-red-700">Cancelled</p>
                    <p class="text-2xl font-bold mt-1">{{ $cancelledOrders }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-5">
            <h2 class="text-lg font-semibold mb-4">Quick Insights</h2>

            <div class="space-y-4 text-sm">
                <div class="flex items-center justify-between border-b pb-3">
                    <span class="text-gray-500">Delivery Orders</span>
                    <span class="font-semibold">{{ $deliveryOrders }}</span>
                </div>

                <div class="flex items-center justify-between border-b pb-3">
                    <span class="text-gray-500">Pickup Orders</span>
                    <span class="font-semibold">{{ $pickupOrders }}</span>
                </div>

                <div class="flex items-center justify-between border-b pb-3">
                    <span class="text-gray-500">Active Materials</span>
                    <span class="font-semibold">{{ $activeMaterials }}</span>
                </div>

                <div class="flex items-center justify-between border-b pb-3">
                    <span class="text-gray-500">Inactive Materials</span>
                    <span class="font-semibold">{{ $inactiveMaterials }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Total Materials</span>
                    <span class="font-semibold">{{ $totalMaterials }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Material dan ringkasan --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow p-5">
            <h2 class="text-lg font-semibold mb-4">Material Overview</h2>

            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span>Active</span>
                        <span>{{ $totalMaterials > 0 ? round(($activeMaterials / $totalMaterials) * 100) : 0 }}%</span>
                    </div>
                    <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-green-500 rounded-full"
                             style="width: {{ $totalMaterials > 0 ? ($activeMaterials / $totalMaterials) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span>Inactive</span>
                        <span>{{ $totalMaterials > 0 ? round(($inactiveMaterials / $totalMaterials) * 100) : 0 }}%</span>
                    </div>
                    <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gray-500 rounded-full"
                             style="width: {{ $totalMaterials > 0 ? ($inactiveMaterials / $totalMaterials) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <a href="{{ route('admin.materials.index') }}"
                   class="inline-block bg-slate-900 text-white px-4 py-2 rounded-lg text-sm">
                    Kelola Materials
                </a>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl shadow p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Recent Orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-blue-600 hover:underline">
                    Lihat semua
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b bg-gray-50 text-left">
                            <th class="py-3 px-3">Order Code</th>
                            <th class="py-3 px-3">User</th>
                            <th class="py-3 px-3">Project</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3">Total</th>
                            <th class="py-3 px-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
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
                                <td class="py-3 px-3 font-medium">{{ $order->order_code }}</td>
                                <td class="py-3 px-3">{{ $order->user->name ?? '-' }}</td>
                                <td class="py-3 px-3">{{ $order->project->title ?? '-' }}</td>
                                <td class="py-3 px-3">
                                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClasses }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="py-3 px-3">Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}</td>
                                <td class="py-3 px-3">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="text-blue-600 hover:underline font-medium">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 px-3 text-center text-gray-500">
                                    Belum ada order pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection