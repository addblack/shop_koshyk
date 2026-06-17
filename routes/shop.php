<?php

use App\Http\Controllers\Shop\AccountController;
use App\Http\Controllers\Shop\AdminController;
use App\Http\Controllers\Shop\AuthController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

// ── Каталог ──────────────────────────────────────────────────
Route::get('/',               [ProductController::class, 'index'])->name('shop.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('shop.product');

// ── Кошик (сторінки) ─────────────────────────────────────────
Route::get('/cart',                   [CartController::class, 'index'])->name('shop.cart');
Route::get('/cart/success/{order}',   [CartController::class, 'orderSuccess'])->name('shop.order-success');
Route::post('/cart/checkout',         [CartController::class, 'checkout'])->name('shop.checkout');

// ── Кошик (AJAX) ─────────────────────────────────────────────
Route::post('/cart/add',    [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update',[CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove',[CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/mini',    [CartController::class, 'mini'])->name('cart.mini');

// ── Auth ─────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'loginForm'])->name('shop.login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'registerForm'])->name('shop.register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('shop.logout')->middleware('auth');

// ── Особистий кабінет ─────────────────────────────────────────
Route::middleware('auth')->prefix('account')->group(function () {
    Route::get('/orders', [AccountController::class, 'orders'])->name('shop.account.orders');
});

// ── Адмін-панель ──────────────────────────────────────────────
Route::middleware(['auth', EnsureAdmin::class])->prefix('admin')->group(function () {
    Route::get('/orders',              [AdminController::class, 'orders'])->name('shop.admin.orders');
    Route::patch('/orders/{order}/status', [AdminController::class, 'updateStatus'])->name('shop.admin.orders.status');
});
