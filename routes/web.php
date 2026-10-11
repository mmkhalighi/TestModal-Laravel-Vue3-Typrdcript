<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TrainingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\User\FavoriteController;
use App\Http\Controllers\User\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::resource('users', UserController::class)
            ->except(['show', 'create', 'edit']);

        Route::put('/users/{user}/password', [UserPasswordController::class, 'update'])
            ->name('users.password.update');

        Route::post('/trainings/check-name', [TrainingController::class, 'checkName'])
            ->name('trainings.check-name');

        Route::resource('trainings', TrainingController::class)
            ->except(['create', 'edit']);
    });

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', App\Http\Controllers\User\DashboardController::class)
        ->name('dashboard');

    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])
        ->name('settings.password.update');

    Route::get('/trainings/{training}', [\App\Http\Controllers\User\TrainingController::class, 'show'])
        ->name('user.trainings.show');

    Route::get('/favorites', [FavoriteController::class, 'index'])
        ->name('user.favorites.index');
    Route::put('/favorites/{trainingNode}', [FavoriteController::class, 'store'])
        ->name('user.favorites.store');
    Route::delete('/favorites/{trainingNode}', [FavoriteController::class, 'destroy'])
        ->name('user.favorites.destroy');
});
