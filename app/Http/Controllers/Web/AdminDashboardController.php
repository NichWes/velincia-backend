<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Material;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->get('range', '30d');

        $ordersBaseQuery = Order::query();

        if ($range === 'today') {
            $ordersBaseQuery->whereDate('created_at', today());
        } elseif ($range === '7d') {
            $ordersBaseQuery->where('created_at', '>=', now()->subDays(7));
        } elseif ($range === '30d') {
            $ordersBaseQuery->where('created_at', '>=', now()->subDays(30));
        }
        // all = tanpa filter waktu

        $orders = (clone $ordersBaseQuery)->get(['id', 'status', 'total_amount', 'delivery_method']);

        $totalOrders = $orders->count();
        $draftOrders = $orders->where('status', 'draft')->count();
        $waitingAdminOrders = $orders->where('status', 'waiting_admin')->count();
        $waitingPaymentOrders = $orders->where('status', 'waiting_payment')->count();
        $paidOrders = $orders->where('status', 'paid')->count();
        $processingOrders = $orders->where('status', 'processing')->count();
        $shippedOrders = $orders->where('status', 'shipped')->count();
        $readyPickupOrders = $orders->where('status', 'ready_pickup')->count();
        $completedOrders = $orders->where('status', 'completed')->count();
        $cancelledOrders = $orders->where('status', 'cancelled')->count();

        $pendingOrders = $waitingAdminOrders + $waitingPaymentOrders + $paidOrders + $processingOrders;

        $totalRevenue = $orders->where('status', 'completed')->sum('total_amount');
        $potentialRevenue = $orders
            ->whereIn('status', ['waiting_admin', 'waiting_payment', 'paid', 'processing', 'shipped', 'ready_pickup'])
            ->sum('total_amount');
        $averageOrderValue = $completedOrders > 0
        ? $totalRevenue / $completedOrders
        : 0;

        $completionRate = $totalOrders > 0
            ? ($completedOrders / $totalOrders) * 100
            : 0;

        $cancellationRate = $totalOrders > 0
            ? ($cancelledOrders / $totalOrders) * 100
            : 0;

        $pickupOrders = $orders->where('delivery_method', 'pickup')->count();
        $deliveryOrders = $orders->where('delivery_method', 'delivery')->count();

        $activeMaterials = Material::where('is_active', true)->count();
        $inactiveMaterials = Material::where('is_active', false)->count();
        $totalMaterials = $activeMaterials + $inactiveMaterials;

        $recentOrdersQuery = Order::select([
            'id',
            'user_id',
            'project_id',
            'order_code',
            'status',
            'total_amount',
            'created_at',
        ])
        ->with(['user:id,name', 'project:id,title'])
        ->latest();

        if ($range === 'today') {
            $recentOrdersQuery->whereDate('created_at', today());
        } elseif ($range === '7d') {
            $recentOrdersQuery->where('created_at', '>=', now()->subDays(7));
        } elseif ($range === '30d') {
            $recentOrdersQuery->where('created_at', '>=', now()->subDays(30));
        }

        $recentOrders = $recentOrdersQuery
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'range',
            'totalOrders',
            'draftOrders',
            'waitingAdminOrders',
            'waitingPaymentOrders',
            'paidOrders',
            'processingOrders',
            'shippedOrders',
            'readyPickupOrders',
            'completedOrders',
            'cancelledOrders',
            'pendingOrders',
            'totalRevenue',
            'potentialRevenue',
            'pickupOrders',
            'deliveryOrders',
            'activeMaterials',
            'inactiveMaterials',
            'totalMaterials',
            'recentOrders',
            'averageOrderValue',
            'completionRate',
            'cancellationRate',
        ));
    }
}