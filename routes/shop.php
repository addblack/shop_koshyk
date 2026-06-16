<?php

use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\ProductController;
use Illuminate\Support\Facades\Route;

// Каталог
Route::get('/',          [ProductController::class, 'index'])->name('shop.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('shop.product');

// Кошик — сторінки
Route::get('/cart',          [CartController::class, 'index'])->name('shop.cart');
Route::get('/cart/success/{order}', [CartController::class, 'orderSuccess'])->name('shop.order-success');
Route::post('/cart/checkout',[CartController::class, 'checkout'])->name('shop.checkout');

// Кошик — AJAX API
Route::post('/cart/add',    [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update',[CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove',[CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/mini',    [CartController::class, 'mini'])->name('cart.mini');
