<?php

use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\WarehousesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
Route::post('/orders', [OrdersController::class, 'store'])->name('orders.store');
Route::put('/orders/{order}', [OrdersController::class, 'update'])->name('orders.update');
Route::patch('/orders/{order}/complete', [OrdersController::class, 'complete'])->name('orders.complete');
Route::patch('/orders/{order}/renew', [OrdersController::class, 'renew'])->name('orders.renew');
Route::patch('/orders/{order}/cancel',[OrdersController::class, 'cancel'])->name('orders.cancel');

Route::get('/warehouses', [WarehousesController::class, 'index'])->name('warehouses.index');
Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
