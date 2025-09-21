<?php

use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

Route::prefix('admin')->middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});
