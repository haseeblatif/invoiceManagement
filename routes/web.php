<?php

use App\Http\Controllers\VendorAuthController;
use App\Http\Controllers\ManagerAuthController;
use App\Http\Controllers\AccountantAuthController;
use App\Http\Controllers\InvoicesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('vendor')->group(function () {
    Route::get('login', [VendorAuthController::class, 'showLoginForm'])->name('vendor.login');
    Route::post('login', [VendorAuthController::class, 'login']);
    Route::post('logout', [VendorAuthController::class, 'logout'])->name('vendor.logout');
    Route::middleware(['auth:vendor', 'role:vendor'])->group(function () {
        Route::get('dashboard', [InvoicesController::class, 'vendorDashboard'])->name('vendor.dashboard');
        Route::post('invoices', [InvoicesController::class, 'create'])->name('invoices.create');
                Route::put('invoices/{invoice}/reupload', [InvoicesController::class, 'reupload'])->name('invoices.reupload');

    });
});

Route::prefix('manager')->group(function () {
    Route::get('login', [ManagerAuthController::class, 'showLoginForm'])->name('manager.login');
    Route::post('login', [ManagerAuthController::class, 'login']);
    Route::post('logout', [ManagerAuthController::class, 'logout'])->name('manager.logout');
    Route::middleware(['auth:manager', 'role:manager'])->group(function () {
        Route::get('dashboard', [InvoicesController::class, 'managerDashboard'])->name('manager.dashboard');
        Route::put('invoices/{invoice}/status', [InvoicesController::class, 'updateStatus'])->name('invoices.updateStatus');
    });
});

Route::prefix('accountant')->group(function () {
    Route::get('login', [AccountantAuthController::class, 'showLoginForm'])->name('accountant.login');
    Route::post('login', [AccountantAuthController::class, 'login']);
    Route::post('logout', [AccountantAuthController::class, 'logout'])->name('accountant.logout');
    Route::middleware(['auth:accountant', 'role:accountant'])->group(function () {
        Route::get('dashboard', [InvoicesController::class, 'accountantDashboard'])->name('accountant.dashboard');
        Route::put('invoices/{invoice}/pay', [InvoicesController::class, 'markPaid'])->name('invoices.markPaid');
    });
});