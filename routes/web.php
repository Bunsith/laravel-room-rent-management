<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('rooms', RoomController::class)->except(['show']);
    Route::resource('floors', FloorController::class)->except(['show']);
    Route::resource('room-types', RoomTypeController::class)->except(['show']);
    Route::resource('facilities', FacilityController::class)->except(['show']);
    Route::resource('customers', CustomerController::class);

    Route::get('rentals', [RentalController::class, 'index'])->name('rentals.index');
    Route::post('rentals', [RentalController::class, 'store'])->name('rentals.store');
    Route::patch('rentals/{rental}', [RentalController::class, 'update'])->name('rentals.update');
    Route::post('rentals/{rental}/checkout', [RentalController::class, 'checkOut'])->name('rentals.checkout');

    Route::resource('invoices', InvoiceController::class)->only(['index', 'show', 'destroy']);
    Route::post('invoices/{invoice}/pay', [InvoiceController::class, 'pay'])->name('invoices.pay');
    Route::patch('invoices/{invoice}/electric', [InvoiceController::class, 'updateElectric'])->name('invoices.electric.update');
    Route::patch('invoices/{invoice}/water', [InvoiceController::class, 'updateWater'])->name('invoices.water.update');
    Route::patch('invoices/{invoice}/utilities', [InvoiceController::class, 'updateUtilities'])->name('invoices.utilities.update');
    Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');

    Route::resource('journal-entries', JournalEntryController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::get('users/permissions', [RolePermissionController::class, 'edit'])->name('users.permissions.edit');
    Route::put('users/permissions', [RolePermissionController::class, 'update'])->name('users.permissions.update');
    Route::resource('users', UserController::class)->except(['show']);

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
});
