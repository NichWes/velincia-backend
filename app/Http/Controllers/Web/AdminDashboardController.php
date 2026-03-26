<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;

class AdminDashboardController extends Controller {
    public function index() {
        $totalOrders = Order::count();
        $pendingOrders = Order::whereIn('status', ['waiting_admin', 'waiting_payment', 'paid', 'processing'])->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');

        $recentOrders = Order::with(['user', 'project'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'cancelledOrders',
            'totalRevenue',
            'recentOrders'
        ));
    }
}