<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});

// Apply auth middleware to the PostController resource routes
Route::middleware(['auth'])->group(function () {
    Route::resource('posts', PostController::class);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Apply auth middleware to the /admin route
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/test', function () {
        return view('admin.test');
    });
});
