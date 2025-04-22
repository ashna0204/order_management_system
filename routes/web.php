<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use Barryvdh\DomPDF\Facade\Pdf;


Route::get('/', [OrderController::class,'index']);

// Route::get('/', [OrderController::class, 'index'])->name('orders.index');
// Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
// Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
// Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
// Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
// Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
// Route::get('/customers/{id}', [\App\Http\Controllers\CustomerController::class, 'getCustomer']);

// Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');


Route::prefix('orders')->name('orders.')->controller(OrderController::class)->group (function(){

    Route::get('/trash','trash')->name('trash');
    Route::post('/{id}/restore','restore')->name('restore');
    Route::delete('/{id}/forceDelete','forceDelete')->name('forceDelete');
    Route::delete('/{id}','destroy')->name('destroy');
    Route::get('/export-pdf','exportPdf')->name('exportPdf');

});

Route::resource('orders', OrderController::class);

// Define the route for retrieving a customer
Route::get('/customers/{id}', [CustomerController::class, 'getCustomer']);


Route::post('/products', [App\Http\Controllers\OrderController::class, 'productStore'])->name('products.store');

Route::prefix('products')->name('products.')->controller(OrderController::class)->group (function(){
    Route::get('/create','productForm')->name('create');
    Route::get('/show','productShow')->name('show');
    Route::get('/delete/{id}','productDelete')->name('delete');
    Route::get('/edit/{id}','productEdit')->name('edit');
    Route::put('/update/{id}','productUpdate')->name('update');
    

   
});


