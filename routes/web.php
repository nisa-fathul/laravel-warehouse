<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use PhpParser\Node\Expr\Assign;

Route::get('/', function () {
    return view('pages.forecast');
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
