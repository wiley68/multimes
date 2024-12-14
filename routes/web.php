<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\FactoryController;
use App\Http\Controllers\MhallController;
use App\Http\Controllers\MproductionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissinController;
use App\Http\Controllers\RemoveRoleFromUserController;
use App\Http\Controllers\RevokePermissionFromRoleController;
use App\Http\Controllers\RevokePermissionFromUserController;
use App\Http\Controllers\UhallController;
use App\Http\Controllers\UproductionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'version' => env('APP_VERSION', '1.0.1'),
    ]);
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::resource('/users', UserController::class);
    Route::resource('/roles', RoleController::class);
    Route::resource('/permissions', PermissinController::class);
    Route::delete('/roles/{role}/permissions/{permission}', RevokePermissionFromRoleController::class)->name('roles.permissions.destroy');
    Route::delete('/users/{user}/roles/{role}', RemoveRoleFromUserController::class)->name('users.roles.destroy');
    Route::delete('/users/{user}/permissions/{permission}', RevokePermissionFromUserController::class)->name('users.permissions.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'permission:create|update|delete|view'])->group(function () {
    Route::resource('/cities', CityController::class);
    Route::resource('/factories', FactoryController::class);
    Route::resource('/mhalls', MhallController::class);
    Route::get('/mhalls/show', [MhallController::class, 'show'])->name('mhalls.show');
    Route::resource('/uhalls', UhallController::class);
    Route::get('/uhalls/show', [UhallController::class, 'show'])->name('uhalls.show');
    Route::get('/mproductions', [MproductionController::class, 'index'])->name('mproductions.index');
    Route::get('/mproductions/create', [MproductionController::class, 'create'])->name('mproductions.create');
    Route::post('/mproductions', [MproductionController::class, 'store'])->name('mproductions.store');
    Route::get('/mproductions/{mproduction}', [MproductionController::class, 'show'])->name('mproductions.show');
    Route::put('/mproductions/{mproduction}', [MproductionController::class, 'update'])->name('mproductions.update');
    Route::delete('/mproductions/{mproduction}', [MproductionController::class, 'destroy'])->name('mproductions.destroy');
    Route::get('/uproductions', [UproductionController::class, 'index'])->name('uproductions.index');
    Route::get('/uproductions/create', [UproductionController::class, 'create'])->name('uproductions.create');
    Route::post('/uproductions', [UproductionController::class, 'store'])->name('uproductions.store');
    Route::get('/uproductions/{uproduction}', [UproductionController::class, 'show'])->name('uproductions.show');
    Route::put('/uproductions/{uproduction}', [UproductionController::class, 'update'])->name('uproductions.update');
    Route::delete('/uproductions/{uproduction}', [UproductionController::class, 'destroy'])->name('uproductions.destroy');
});

require __DIR__ . '/auth.php';
