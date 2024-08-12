<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BranchUserContoller;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
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


    Route::get('/article',[ArticleController::class,'index'])->name('articles.list');
    Route::get('/article/create',[ArticleController::class,'create'])->name('articles.create');
    Route::post('/article/store',[ArticleController::class,'store'])->name('articles.store');
    Route::get('/article/edit/{id}',[ArticleController::class,'edit'])->name('articles.edit');
    Route::post('/article/update',[ArticleController::class,'update'])->name('articles.update');
    Route::delete('/article/delete/{id}',[ArticleController::class,'destroy'])->name('articles.destroy');

    Route::get('/user',[BranchController::class,'index'])->name('users.list');
    Route::get('/user/create',[BranchUserContoller::class,'create'])->name('users.create');
    Route::post('/user/store',[BranchUserContoller::class,'store'])->name('users.store');
    Route::get('/user/edit/{id}',[BranchUserContoller::class,'edit'])->name('users.edit');
    Route::post('/user/update',[BranchUserContoller::class,'update'])->name('users.update');
    Route::delete('/user/delete/{id}',[BranchUserContoller::class,'destroy'])->name('users.destroy');

});

require __DIR__.'/auth.php';
require __DIR__.'/admin-auth.php';
