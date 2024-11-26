<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/', 'index')->name('home.index');
    });

    Route::controller(RoomController::class)->group(function () {
        Route::get('/rooms/{room}', 'index')->name('rooms.index');
        Route::post('/rooms', 'store')->name('rooms.store');
        Route::post('/rooms/{room}/move', 'move')->name('rooms.move');
        Route::get('/rooms/{room}/join', 'join')->name('rooms.join');
        Route::post('/rooms/{room}/refresh', 'refresh')->name('rooms.refresh');
    });

    Route::get('/profile', [UserController::class, 'show'])->name('profile');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware([RedirectIfAuthenticated::class])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'indexLogin')->name('login');
        Route::get('/register', 'indexRegister')->name('register');
        Route::post('/login', 'login')->name('login.submit');
        Route::post('/register', 'register')->name('register.submit');
    });
});
