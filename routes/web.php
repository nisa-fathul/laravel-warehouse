<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/login-process', [AuthController::class, 'authenticate'])->name('login.authenticate');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['Authorize_Access:Admin'])->group(function () {
        Route::prefix('inventory')->name('inventory.')->group(function () {
            Route::get('/', action: [BarangController::class, 'index'])->name('index');
            Route::post('/create', [BarangController::class, 'create'])->name('create');
            Route::put('/update/{id}', [BarangController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [BarangController::class, 'destroy'])->name('destroy');
        });

        Route::apiResource('/role', RolesController::class);
        // Route::prefix('permission')->name('permission.')->group(function () {
        //     Route::get('/', [PermissionController::class, 'index'])
        //         ->name('index');
        //     Route::post('/', [PermissionController::class, 'store'])
        //         ->name('store');
        //     Route::put('/{id_permission}', [PermissionController::class, 'update'])
        //         ->name('update');
        //     Route::delete('/mass-delete', [PermissionController::class, 'destroy'])
        //         ->name('destroy');
        // });

        // Route::get('users/assign', [UserController::class, 'assignIndex'])->name('user.assign.index');
        // Route::put('users/{id}/assign_roles', [UserController::class, 'assignRoles'])->name('user.assign.roles.update');
        // Route::put('users/{id}/assign_permisson', [UserController::class, 'assignPermission'])->name('user.assign.permissions.update');
    });

    Route::middleware(['Authorize_Access:Admin|Manajemen|Staf Gudang'])->group(function () {
        Route::prefix('forecast')->name('forecast.')->group(function () {
                Route::get('/', [ForecastController::class, 'index'])->name('index');
        });
    });

    Route::middleware(['Authorize_Access:Staf Gudang'])->group(function () {
        Route::prefix('transaction')->name('transaction.')->group(function () {
            Route::get('/{type}', [TransaksiController::class, 'in'])->name('index');
            Route::post('/in', [TransaksiController::class, 'storeIn'])->name('in.store');
            Route::put('/in/{id_transaksi}', [TransaksiController::class, 'updateIn'])->name('in.update');
            Route::get('/{type}/{action}/{id}', [TransaksiController::class, 'detail'])->name('detail');
            Route::post('/out', [TransaksiController::class, 'storeOut'])->name('out.store');
            Route::put('/out/{id_transaksi}', [TransaksiController::class, 'updateOut'])->name('out.update');
        });
    });

    Route::middleware(['Authorize_Access:Manajemen|Staf Gudang'])->group(function () {
        Route::prefix('report')->name('report.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
        });
    });
});
