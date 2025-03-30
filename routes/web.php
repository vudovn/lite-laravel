<?php

use Framework\Facades\Route;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;

// Basic Routes
Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [HomeController::class, 'about']);
Route::get('/contact', [HomeController::class, 'contact']);

// Authentication Routes
Route::get('/register', [AuthController::class, 'showRegisterForm']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Dashboard routes with prefix
Route::group(['prefix' => 'dashboard'], function ($router) {
    $router->get('', [DashboardController::class, 'index']);
    $router->get('/profile', [DashboardController::class, 'profile']);
});
Route::get('/dashboard', [DashboardController::class, 'index']);