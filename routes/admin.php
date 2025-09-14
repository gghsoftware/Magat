<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
<<<<<<< HEAD
=======
use App\Http\Controllers\Admin\PaymentController;
>>>>>>> 54d403e (Initial commit of Magat Funeral project)

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| URLs will look like:
|   /admin/login           -> admin.login (GET)
|   /admin/login           -> admin.login.post (POST)
|   /admin/logout          -> admin.logout (POST)
|   /admin/dashboard       -> admin.dashboard (GET, protected)
|   /admin/orders          -> admin.orders.index (GET, protected)
|   ...etc
|
| Requires:
|   - config/auth.php has a guard 'admin' with provider 'admins'
|   - controllers use Auth::guard('admin')
*/

// Group all under /admin and name prefix admin.
Route::prefix('admin')->as('admin.')->group(function () {

    // -------- Auth (guest:admin) --------
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login',  [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'login'])->name('login.post');
    });

    // Logout (auth:admin)
    Route::post('/logout', [AdminLoginController::class, 'logout'])
        ->middleware('auth:admin')->name('logout');

    // -------- Protected Admin Area (auth:admin) --------
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/orders',     [OrderController::class, 'index'])->name('orders.index');

        Route::get('/users',      [UserController::class, 'index'])->name('users.index');

        Route::get('/packages',   [PackageController::class, 'index'])->name('packages.index');

        // Inventory
        Route::get('/inventory',  [InventoryController::class, 'index'])->name('inventory.index');
        Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
        Route::put('/inventory/{product}', [InventoryController::class, 'update'])->name('inventory.update'); // NEW
        Route::delete('/inventory/{product}', [InventoryController::class, 'destroy'])
            ->name('inventory.destroy');

        Route::get('/reports',    [ReportController::class, 'index'])->name('reports.index');

        Route::get('/customers',  [CustomerController::class, 'index'])->name('customers.index');

        // Example: products CRUD for admin
        Route::resource('/products', ProductController::class)->names('products');
<<<<<<< HEAD
    });
=======


            Route::post('/payments/{payment}/approve', [PaymentController::class, 'approve'])->name('payments.approve');
            Route::post('/payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');

            // Payments list
    Route::get('/payments', [PaymentController::class, 'index'])
    ->name('payments.index');

// Approve payment
Route::post('/payments/{payment}/approve', [PaymentController::class, 'approve'])
    ->name('payments.approve');

// Reject payment
Route::post('/payments/{payment}/reject', [PaymentController::class, 'reject'])
    ->name('payments.reject');

    Route::resource('packages', PackageController::class)->except(['show']);

      // Orders list & detail
      Route::get('/orders',        [OrderController::class, 'index'])->name('orders.index');
      Route::get('/orders/{order}',[OrderController::class, 'show'])->name('orders.show');

      // Actions
      Route::post('/orders/{order}/confirm',  [OrderController::class, 'confirm'])->name('orders.confirm');
      Route::post('/orders/{order}/mark-paid',[OrderController::class, 'markPaid'])->name('orders.markPaid');
      Route::post('/orders/{order}/cancel',   [OrderController::class, 'cancel'])->name('orders.cancel');

      // Optional hard delete
      Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

   
    // Customers
    Route::get('/customers',                     [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create',              [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers',                    [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}',          [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/edit',     [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}',          [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}',       [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::post('/customers/{customer}/toggle-status',[CustomerController::class, 'toggleStatus'])->name('customers.toggle');

  });

>>>>>>> 54d403e (Initial commit of Magat Funeral project)
});
