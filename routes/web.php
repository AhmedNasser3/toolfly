<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\frontend\home\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home.page');
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
Route::get('/authstore',[AuthController::class, 'storeAuth'])->name('storeAuth');
Route::get('/authstore/callback',[AuthController::class, 'callback'])->name('callback');