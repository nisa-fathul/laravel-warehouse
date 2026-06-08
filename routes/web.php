<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use PhpParser\Node\Expr\Assign;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('inventory')->name('inventory.')->group(function(){
    Route::get('/',[BarangController::class,'index'])->name('index');
});

Route::prefix('forecast')->name('forecast.')->group(function(){
    Route::get('/',[ForecastController::class,'index'])->name('index');
});

Route::prefix('transaction')->name('transaction.')->group(function(){
    Route::get('/in',[TransaksiController::class,'in'])->name('in');
    Route::get('/out',[TransaksiController::class,'out'])->name('out');
});

Route::prefix('report')->name('report.')->group(function(){
    Route::get('/', [ReportController::class, 'index'])->name('index');
});

Route::apiResource('/role', RolesController::class);
Route::prefix('permission')->name('permission.')->group(function(){
    Route::get('/',[PermissionController::class,'index'])
    ->name('index');
    Route::post('/',[PermissionController::class,'store'])
    ->name('store');
    Route::put('/{id_permission}',[PermissionController::class,'update'])
    ->name('update');
    Route::delete('/mass-delete',[PermissionController::class,'destroy'])
    ->name('destroy');
});

Route::get('users/assign',[UserController::class,'assignIndex'])->name('user.assign.index');
Route::put('users/{id}/assign_roles',[UserController::class,'assignRoles'])->name('user.assign.roles.update');
Route::put('users/{id}/assign_permisson',[UserController::class,'assignPermission'])->name('user.assign.permissions.update');
