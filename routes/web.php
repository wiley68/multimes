<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\FactoryController;
use App\Http\Controllers\MdecrementController;
use App\Http\Controllers\MhallController;
use App\Http\Controllers\MincrementController;
use App\Http\Controllers\MproductionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissinController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RemoveRoleFromUserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RevokePermissionFromRoleController;
use App\Http\Controllers\RevokePermissionFromUserController;
use App\Http\Controllers\SiloController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SubdeliveryController;
use App\Http\Controllers\UdecrementController;
use App\Http\Controllers\UhallController;
use App\Http\Controllers\UincrementController;
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

Route::middleware(['auth', 'exclude.admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'exclude.admin', 'permission:create|update|delete|view'])->group(function () {
    Route::resource('/cities', CityController::class);
    Route::resource('/factories', FactoryController::class);
    Route::resource('/silos', SiloController::class);
    Route::get('/silos/{silo}/loading/uproductions/{uproduction}', [SiloController::class, 'loading'])->name('silos.loading');
    Route::put('/silos/{silo}/load/uproductions/{uproduction}', [SiloController::class, 'load'])->name('silos.load');
    Route::get('/silos/{silo}/mloading/mproductions/{mproduction}', [SiloController::class, 'mloading'])->name('silos.mloading');
    Route::put('/silos/{silo}/mload/mproductions/{mproduction}', [SiloController::class, 'mload'])->name('silos.mload');
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
    Route::get('/mproductions/{mproduction}/loading', [MproductionController::class, 'loading'])->name('mproductions.loading');
    Route::put('/mproductions/{mproduction}/load', [MproductionController::class, 'load'])->name('mproductions.load');
    Route::patch('/mproductions/{mproduction}/complete', [MproductionController::class, 'complete'])->name('mproductions.complete');
    Route::get('/uproductions', [UproductionController::class, 'index'])->name('uproductions.index');
    Route::get('/uproductions/create', [UproductionController::class, 'create'])->name('uproductions.create');
    Route::post('/uproductions', [UproductionController::class, 'store'])->name('uproductions.store');
    Route::get('/uproductions/{uproduction}', [UproductionController::class, 'show'])->name('uproductions.show');
    Route::put('/uproductions/{uproduction}', [UproductionController::class, 'update'])->name('uproductions.update');
    Route::delete('/uproductions/{uproduction}', [UproductionController::class, 'destroy'])->name('uproductions.destroy');
    Route::get('/uproductions/{uproduction}/loading', [UproductionController::class, 'loading'])->name('uproductions.loading');
    Route::put('/uproductions/{uproduction}/load', [UproductionController::class, 'load'])->name('uproductions.load');
    Route::patch('/uproductions/{uproduction}/complete', [UproductionController::class, 'complete'])->name('uproductions.complete');
    Route::resource('/products', ProductController::class);
    Route::resource('/deliveries', DeliveryController::class);
    Route::patch('/deliveries/{delivery}/complete', [DeliveryController::class, 'complete'])->name('deliveries.complete');
    Route::resource('/subdeliveries', SubdeliveryController::class);
    Route::resource('/stores', StoreController::class);
    Route::resource('/udecrements', UdecrementController::class);
    Route::patch('/udecrements/{udecrement}/complete', [UdecrementController::class, 'complete'])->name('udecrements.complete');
    Route::resource('/uincrements', UincrementController::class);
    Route::patch('/uincrements/{uincrement}/complete', [UincrementController::class, 'complete'])->name('uincrements.complete');
    Route::resource('/mdecrements', MdecrementController::class);
    Route::patch('/mdecrements/{mdecrement}/complete', [MdecrementController::class, 'complete'])->name('mdecrements.complete');
    Route::resource('/mincrements', MincrementController::class);
    Route::patch('/mincrements/{mincrement}/complete', [MincrementController::class, 'complete'])->name('mincrements.complete');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

require __DIR__ . '/auth.php';
