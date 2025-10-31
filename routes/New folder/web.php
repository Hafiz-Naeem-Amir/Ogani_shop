<?php

use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

Route::redirect('/', '/login');
Route::get('/register', [UserController::class, 'create'])->name('custom.register');
Route::post('/register', [UserController::class, 'store'])->name('custom.register.store');
Route::get('/login', [UserController::class, 'loginForm'])->name('custom.login');
Route::post('/login', [UserController::class, 'login'])->name('custom.login.submit');
Route::view('/dashboard', 'dashboard')->name('dashboard');
Route::post('/logout', [UserController::class, 'logout'])->name('custom.logout');

Route::get('/roles', [RoleController::class, 'index'])->name('role.index');
Route::get('/roles/create', [RoleController::class, 'create'])->name('role.create');
Route::post('/roles/store', [RoleController::class, 'store'])->name('role.store');
Route::get('/roles/{id}', [RoleController::class, 'edit'])->name('role.edit');
Route::post('/roles/{id}/update', [RoleController::class, 'update'])->name('role.update');
Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('role.destroy');

Route::get('/permission', [PermissionController::class, 'index'])->name('permission.index');
Route::get('/permission/create', [PermissionController::class, 'create'])->name('permission.create');
Route::post('/permission/store', [PermissionController::class, 'store'])->name('permission.store');
Route::get('/permission/{id}/edit', [PermissionController::class, 'edit'])->name('permission.edit');
Route::put('/permission/{id}update', [PermissionController::class, 'update'])->name('permission.update');
Route::delete('/permission/{id}', [PermissionController::class, 'destroy'])->name('permission.destroy');





Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/data', [UserController::class, 'getData'])->name('users.data');

