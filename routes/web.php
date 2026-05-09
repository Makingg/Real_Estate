<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ViewingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showWelcome'])->name('welcome');
Route::get('/browse', [PropertyController::class, 'publicIndex'])->name('browse.index');
Route::get('/browse/{property}', [PropertyController::class, 'publicShow'])->name('browse.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::patch('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');

    Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
    Route::get('/properties/create', [PropertyController::class, 'create'])->name('properties.create');
    Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
    Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');
    Route::get('/properties/{property}/edit', [PropertyController::class, 'edit'])->name('properties.edit');
    Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('properties.update');
    Route::patch('/properties/{property}/approve', [PropertyController::class, 'approve'])->name('properties.approve');

    Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
    Route::post('/properties/{property}/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');
    Route::patch('/inquiries/{inquiry}', [InquiryController::class, 'update'])->name('inquiries.update');

    Route::get('/viewings', [ViewingController::class, 'index'])->name('viewings.index');
    Route::post('/properties/{property}/viewings', [ViewingController::class, 'store'])->name('viewings.store');
    Route::patch('/viewings/{viewing}', [ViewingController::class, 'update'])->name('viewings.update');

    Route::get('/offers', [OfferController::class, 'index'])->name('offers.index');
    Route::post('/properties/{property}/offers', [OfferController::class, 'store'])->name('offers.store');
    Route::patch('/offers/{offer}', [OfferController::class, 'update'])->name('offers.update');
    Route::patch('/trackings/{tracking}', [OfferController::class, 'updateTracking'])->name('trackings.update');
});
