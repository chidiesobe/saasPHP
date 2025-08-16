<?php

use App\Http\Controllers\Settings\{
    ProfileController,
    PasswordController,
    TwoFactorController
};
use App\Http\Middleware\{
    MaintenanceModeEnabled,
    PreventAdminAccessToUserArea,
    BlockImpersonatedAccess
};
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('settings')->name('settings.')->middleware([
    'auth',
    'verified',
    MaintenanceModeEnabled::class,
    PreventAdminAccessToUserArea::class,
    BlockImpersonatedAccess::class,
])->group(function () {
    Route::redirect('/', 'settings/profile');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('appearance', fn() => Inertia::render('settings/appearance'))->name('appearance');

    Route::get('security', [TwoFactorController::class, 'edit'])->name('security.edit');
    Route::put('security', [TwoFactorController::class, 'update'])->name('security.update');
});
