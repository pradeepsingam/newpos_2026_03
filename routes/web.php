<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\PluginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TenantController;
use App\Http\Middleware\EnsureActiveSubscription;
use App\Http\Middleware\EnsureBusinessContext;
use App\Http\Middleware\EnsureUserHasRole;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', LogoutController::class)->name('logout');
});

Route::middleware(['auth', EnsureUserHasRole::class . ':' . User::ROLE_SUPERADMIN])->group(function () {
    Route::get('/plugins', [PluginController::class, 'index'])->name('plugins.index');
    Route::get('/tenants', [TenantController::class, 'index'])->name('tenants.index');
    Route::post('/tenants', [TenantController::class, 'store'])->name('tenants.store');
    Route::put('/tenants/{business}', [TenantController::class, 'update'])->name('tenants.update');
    Route::delete('/tenants/{business}', [TenantController::class, 'destroy'])->name('tenants.destroy');
    Route::post('/plugins/{plugin}/assign', [PluginController::class, 'assign'])->name('plugins.assign');
    Route::post('/plugins/{plugin}/deactivate', [PluginController::class, 'deactivate'])->name('plugins.deactivate');
    Route::post('/plugins/upload', [PluginController::class, 'upload'])->name('plugins.upload');
});

Route::middleware(['auth', EnsureBusinessContext::class])->group(function () {
    Route::get('/subscription/renew', [SubscriptionController::class, 'notice'])->name('subscription.notice');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
});

Route::middleware(['auth', EnsureBusinessContext::class, EnsureActiveSubscription::class])->group(function () {
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
    Route::get('/pos/customers/lookup', [PosController::class, 'lookupCustomer'])->name('pos.customers.lookup');
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/sales/{sale}/receipt', [PosController::class, 'receipt'])->name('sales.receipt');
    Route::resource('products', ProductController::class)->except(['show']);
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
});
