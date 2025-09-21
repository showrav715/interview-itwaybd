<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\UserController;


Route::get('/', function () {
    return redirect()->route('sales.index');
});

Route::resource('sales', SaleController::class);
Route::get('sales-trash', [SaleController::class, 'trash'])->name('sales.trash');
Route::post('sales/{id}/restore', [SaleController::class, 'restore'])->name('sales.restore');

Route::resource('products', ProductController::class);
Route::get('products/{product}/price', [ProductController::class, 'price'])->name('products.price');

Route::resource('users', UserController::class);

Route::post('notes', [NoteController::class, 'store'])->name('notes.store');
