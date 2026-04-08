<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\Auth\TransactionAdminController;
use App\Http\Controllers\OrderKasirController;
use App\Http\Controllers\Kasir\KasirDashboardController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Halaman utama (public / tamu)
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/product/{id}', [ProductController::class, 'frontendShow'])->name('product.show');

// =============================
// SHOP untuk Customer
// =============================
Route::middleware(['auth', 'role:customer'])->group(function () {

    // Halaman daftar produk (toko)
    Route::get('/shop', [ProductController::class, 'frontendIndex'])->name('shop.index');

    // Halaman detail produk
    Route::get('/shop/{id}', [ProductController::class, 'frontendShow'])->name('shop.show');

    // Keranjang
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout/process', [OrderController::class, 'processCheckout'])->name('checkout.process');

    // Riwayat pesanan
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('customer.orders.index');
    Route::get('/my-orders/{order}', [OrderController::class, 'show'])->name('customer.orders.show');
    Route::post('/my-orders/{order}/confirm-delivery', [OrderController::class, 'confirmDelivery'])->name('customer.orders.confirmDelivery');
    Route::post('/my-orders/{order}/upload-proof', [OrderController::class, 'uploadProof'])->name('customer.orders.uploadProof');
    Route::delete('/my-orders/{order}/delete-proof', [OrderController::class, 'deleteProof'])->name('customer.orders.deleteProof');

    // Profil customer
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// =============================
// Dashboard & CRUD untuk Admin
// =============================
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('/products', ProductController::class);
    Route::resource('orders', OrderController::class);
    Route::patch('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/confirm-shipping-cost', [OrderController::class, 'confirmShippingCost'])->name('orders.confirm-shipping-cost');
    
    // Payment approval routes
    Route::post('/orders/{order}/payment/approve', [OrderController::class, 'approvePayment'])->name('orders.payment.approve');
    Route::post('/orders/{order}/payment/reject', [OrderController::class, 'rejectPayment'])->name('orders.payment.reject');
    
    Route::resource('/users', UserController::class);
    Route::resource('/categories', CategoryController::class);
    Route::resource('/shippings', ShippingController::class);

    Route::get('/admin/transactions', [TransactionAdminController::class, 'index'])->name('admin.transactions.index');
    Route::get('/admin/transactions/{transaction}', [TransactionAdminController::class, 'show'])->name('admin.transactions.show');
    Route::patch('/admin/transactions/{transaction}/update-status', [TransactionAdminController::class, 'updateStatus'])->name('admin.transactions.update-status');
});

// =============================
// Kasir Route
// =============================
Route::middleware(['auth', 'role:kasir'])
    ->prefix('kasir')
    ->name('kasir.')
    ->group(function () {

        // Dashboard kasir
        Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');

        // Produk kasir
        Route::get('/products', [ProductController::class, 'kasirIndex'])->name('products.index');

        // Order online kasir
        Route::get('/orders', [OrderKasirController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderKasirController::class, 'show'])->name('orders.show');
        Route::get('/orders/{order}/print', [OrderKasirController::class, 'print'])->name('orders.print');
        Route::patch('/orders/{order}/update-status', [OrderKasirController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('/orders/{order}/payment/approve', [OrderKasirController::class, 'approvePayment'])->name('orders.payment.approve');
        Route::post('/orders/{order}/payment/reject', [OrderKasirController::class, 'rejectPayment'])->name('orders.payment.reject');
        Route::post('/orders/{order}/process', [OrderKasirController::class, 'process'])->name('orders.process');
        Route::post('/orders/{order}/complete', [OrderKasirController::class, 'complete'])->name('orders.complete');
        Route::get('/orders/{order}/tracking', [OrderKasirController::class, 'editTracking'])->name('orders.tracking');
        Route::post('/orders/{order}/tracking', [OrderKasirController::class, 'updateTracking'])->name('orders.updateTracking');
        Route::post('/orders/{order}/claim-return', [OrderKasirController::class, 'claimReturn'])->name('orders.claimReturn');
        Route::post('/orders/calculate-late-fee/{orderItem}', [OrderKasirController::class, 'calculateLateFee'])->name('orders.calculateLateFee');
        
        // Konfirmasi ongkos kirim oleh kasir
        Route::post('/orders/{order}/confirm-shipping-cost', [OrderController::class, 'confirmShippingCost'])->name('orders.confirm-shipping-cost');

        // Transaksi kasir
        Route::resource('/transactions', TransactionController::class);
        Route::get('/transactions/{transaction}/print', [TransactionController::class, 'print'])->name('transactions.print');
        Route::post('/transactions/{transaction}/claim-return', [TransactionController::class, 'claimReturn'])->name('transactions.claimReturn');
        Route::post('/transactions/calculate-late-fee/{transactionItem}', [TransactionController::class, 'calculateLateFee'])->name('transactions.calculateLateFee');
    });

// =============================
// Profile (umum)
// =============================
Route::middleware('auth')->group(function () {
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =============================
// Auth Routes
// =============================
require __DIR__ . '/auth.php';
