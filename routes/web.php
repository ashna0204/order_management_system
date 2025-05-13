<?php
// In your routes/web.php file
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserDashboardController;

// Simple home route
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->hasRole('user')) {
            return redirect()->route('user.dashboard');
        }
    }
    return redirect()->route('login');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
});

// Register the trash route BEFORE the resource route to prevent conflicts
Route::get('/orders/trash', [OrderController::class, 'trash'])
    ->name('orders.trash')
    ->middleware(['auth']);

// Add these custom routes BEFORE the resource route
Route::post('/orders/{id}/restore', [OrderController::class, 'restore'])
    ->name('orders.restore')
    ->middleware(['auth']);

Route::delete('/orders/{id}/forceDelete', [OrderController::class, 'forceDelete'])
    ->name('orders.forceDelete')
    ->middleware(['auth']);

// Then register the resource route
Route::resource('orders', OrderController::class)->middleware(['auth']);

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/orders/export-pdf', [OrderController::class, 'exportPdf'])->name('orders.exportPdf');
    
    // Admin routes for managing products
    Route::prefix('products')->name('products.')->controller(ProductController::class)->group(function() {
        Route::get('/create', 'productForm')->name('create');
        Route::get('/show', 'productShow')->name('show');
        Route::get('/delete/{id}', 'productDelete')->name('delete');
        Route::get('/edit/{id}', 'productEdit')->name('edit');
        Route::put('/update/{id}', 'productUpdate')->name('update');
    });
});

// User routes
Route::middleware('auth')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/show', [ProductController::class, 'productShow'])->name('products.show');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Public routes
Route::get('/customers/{id}', [CustomerController::class, 'getCustomer']);
Route::post('/products', [ProductController::class, 'productStore'])->name('products.store');

Route::get('/dashboard', function () {
    if (auth()->check()) {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->hasRole('user')) {
            return redirect()->route('user.dashboard');
        }
    }
    return redirect()->route('login');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::patch('orders/{order}/status',[OrderController::class, 'updateStatus'])->name('orders.updateStatus');

require __DIR__.'/auth.php';