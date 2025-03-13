<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



// Rutas de autenticación
Route::prefix('auth')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'login'])->name('login');
        // Route::get('/register', [AuthController::class, 'register'])->name('register');
    });


    Route::middleware('auth')->group(function () {
        // Route::get('/email/verify', [AuthController::class, 'emailVerify'])->name('verification.notice');
        // Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        //     ->middleware(['auth', 'signed'])
        //     ->name('verification.verify');

        // Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        //     ->middleware(['auth', 'throttle:6,1'])
        //     ->name('verification.send');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

// Redirigir la raíz dependiendo del estado de autenticación
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Rutas de dashboard (autenticadas)
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //Profile Routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
    });

    // Grupo de rutas para Roles y Permisos
    Route::prefix('security')->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/{id}', [RoleController::class, 'show'])->name('roles.show');
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    });

    // Grupo de rutas para Users
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/{id}', [UserController::class, 'show'])->name('users.show');
    });

    // Grupo de rutas para Settings
    Route::prefix('settings')->group(function () {
        Route::get('/app', [SettingsController::class, 'app'])->name('settings.app');
    });
});
