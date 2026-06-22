<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\Admin\ProductionController as AdminProductionController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;

// Rute Pelanggan (Customer Front-end)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [ProductController::class, 'index'])->name('produk');
Route::get('/produk/detail/{id}', [ProductController::class, 'detail'])->name('produk.detail');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/manual', [HomeController::class, 'manual'])->name('manual');

// Autentikasi Pelanggan (Customer Auth)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Keranjang & Belanja (Diproteksi Session Customer)
Route::middleware(['customer.auth'])->group(function () {
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::get('/keranjang/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/keranjang/update', [CartController::class, 'update'])->name('cart.update');
    Route::get('/keranjang/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');
    
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/payment', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::post('/checkout/payment', [CheckoutController::class, 'paymentProcess'])->name('checkout.payment.process');
    Route::get('/riwayat', [CheckoutController::class, 'history'])->name('checkout.history');
    Route::get('/selesai', [CheckoutController::class, 'success'])->name('checkout.success');
});

// Halaman Login Admin
Route::get('/admin', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Panel Admin (Diproteksi Session Admin)
Route::middleware(['admin.auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Kelola Produk Admin
    Route::get('/produk', [AdminProductController::class, 'index'])->name('produk.index');
    Route::get('/produk/create', [AdminProductController::class, 'create'])->name('produk.create');
    Route::post('/produk', [AdminProductController::class, 'store'])->name('produk.store');
    Route::get('/produk/{id}/edit', [AdminProductController::class, 'edit'])->name('produk.edit');
    Route::post('/produk/{id}', [AdminProductController::class, 'update'])->name('produk.update');
    Route::get('/produk/{id}/delete', [AdminProductController::class, 'delete'])->name('produk.delete');
    
    // Kelola Inventory Admin
    Route::get('/inventory', [AdminInventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [AdminInventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [AdminInventoryController::class, 'store'])->name('inventory.store');
    Route::get('/inventory/{id}/edit', [AdminInventoryController::class, 'edit'])->name('inventory.edit');
    Route::post('/inventory/{id}', [AdminInventoryController::class, 'update'])->name('inventory.update');
    Route::get('/inventory/{id}/delete', [AdminInventoryController::class, 'delete'])->name('inventory.delete');
    
    // Kelola Customer Admin
    Route::get('/customer', [AdminCustomerController::class, 'index'])->name('customer.index');
    
    // Kelola Produksi / Pesanan Admin
    Route::get('/produksi', [AdminProductionController::class, 'index'])->name('produksi.index');
    Route::get('/produksi/detail/{invoice}', [AdminProductionController::class, 'detail'])->name('produksi.detail');
    Route::post('/produksi/terima/{invoice}', [AdminProductionController::class, 'accept'])->name('produksi.accept');
    Route::post('/produksi/tolak/{invoice}', [AdminProductionController::class, 'reject'])->name('produksi.reject');
    Route::get('/produksi/konfirmasi-pembayaran/{invoice}', [AdminProductionController::class, 'confirmPayment'])->name('produksi.confirm-payment');
    
    // Kelola Resep (BOM)
    Route::get('/bom', [AdminProductionController::class, 'bom'])->name('bom.index');
});
