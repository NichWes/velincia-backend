<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AdminAuthController;
use App\Http\Controllers\Web\AdminDashboardController;
use App\Http\Controllers\Web\AdminOrderController;
use App\Http\Controllers\Web\AdminMaterialController;
use App\Http\Controllers\Web\CompanyProfileController;

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

Route::get('/', [CompanyProfileController::class, 'home'])->name('home');
Route::get('/products', [CompanyProfileController::class, 'products'])->name('products');
Route::get('/about', [CompanyProfileController::class, 'about'])->name('about');
Route::get('/contact', [CompanyProfileController::class, 'contact'])->name('contact');

Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit')->middleware('throttle:5,1');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');

        Route::post('/orders/{order}/adjust', [AdminOrderController::class, 'adjust'])->name('admin.orders.adjust');
        Route::post('/orders/{order}/set-waiting-payment', [AdminOrderController::class, 'setWaitingPayment'])->name('admin.orders.setWaitingPayment');

        Route::post('/orders/{order}/process', [AdminOrderController::class, 'process'])->name('admin.orders.process');
        Route::post('/orders/{order}/ship', [AdminOrderController::class, 'ship'])->name('admin.orders.ship');
        Route::post('/orders/{order}/ready-pickup', [AdminOrderController::class, 'readyPickup'])->name('admin.orders.readyPickup');
        Route::post('/orders/{order}/complete', [AdminOrderController::class, 'complete'])->name('admin.orders.complete');
        Route::post('/orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('admin.orders.cancel');

        Route::get('/materials', [AdminMaterialController::class, 'index'])->name('admin.materials.index');
        Route::get('/materials/create', [AdminMaterialController::class, 'create'])->name('admin.materials.create');
        Route::post('/materials', [AdminMaterialController::class, 'store'])->name('admin.materials.store');
        Route::get('/materials/{material}/edit', [AdminMaterialController::class, 'edit'])->name('admin.materials.edit');
        Route::put('/materials/{material}', [AdminMaterialController::class, 'update'])->name('admin.materials.update');
        Route::delete('/materials/{material}', [AdminMaterialController::class, 'destroy'])->name('admin.materials.destroy');
        Route::patch('/materials/{material}/toggle-status', [AdminMaterialController::class, 'toggleStatus'])->name('admin.materials.toggleStatus');
    });
});