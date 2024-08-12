<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Admin\AdminUserController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->middleware('guest:admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::get('register', [RegisteredUserController::class, 'create'])->name('admin.register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [LoginController::class, 'create'])->name('admin.login');

    Route::post('login', [LoginController::class, 'store']);


});

Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('/test',[AdminUserController::class,'test'])->name('test');
    Route::get('/user',[AdminUserController::class,'index'])->name('user.list');
    Route::get('/user/create',[AdminUserController::class,'create'])->name('user.create');
    Route::post('/user/store',[AdminUserController::class,'store'])->name('user.store');
    Route::get('/user/edit/{id}',[AdminUserController::class,'edit'])->name('user.edit');
    Route::post('/user/update',[AdminUserController::class,'update'])->name('user.update');
    Route::delete('/user/delete/{id}',[AdminUserController::class,'destroy'])->name('user.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/permission',[PermissionController::class,'index'])->name('permission.list');
    Route::get('/permission/create',[PermissionController::class,'create'])->name('permission.create');
    Route::post('/permission/store',[PermissionController::class,'store'])->name('permission.store');
    Route::get('/permission/edit/{id}',[PermissionController::class,'edit'])->name('permission.edit');
    Route::post('/permission/update',[PermissionController::class,'update'])->name('permission.update');
    Route::delete('/permission/delete/{id}',[PermissionController::class,'destroy'])->name('permission.destroy');

    Route::get('/role',[RoleController::class,'index'])->name('role.list');
    Route::get('/role/create',[RoleController::class,'create'])->name('role.create');
    Route::post('/role/store',[RoleController::class,'store'])->name('role.store');
    Route::get('/role/edit/{id}',[RoleController::class,'edit'])->name('role.edit');
    Route::post('/role/update',[RoleController::class,'update'])->name('role.update');
    Route::delete('/role/delete/{id}',[RoleController::class,'destroy'])->name('role.destroy');

    Route::get('/role/guard_filter',[RoleController::class,'guard_filter'])->name('role.guard_filter');

    Route::get('/article',[ArticleController::class,'index'])->name('article.list');
    Route::get('/article/create',[ArticleController::class,'create'])->name('article.create');
    Route::post('/article/store',[ArticleController::class,'store'])->name('article.store');
    Route::get('/article/edit/{id}',[ArticleController::class,'edit'])->name('article.edit');
    Route::post('/article/update',[ArticleController::class,'update'])->name('article.update');
    Route::delete('/article/delete/{id}',[ArticleController::class,'destroy'])->name('article.destroy');

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::post('logout', [LoginController::class, 'destroy'])->name('admin.logout');
});
