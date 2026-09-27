<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionController;

// Route Autentikasi Session Cookie SPA
Route::post('/login', [SessionController::class, 'login'])
    ->middleware('throttle:api-login')
    ->name('login');

Route::post('/logout', [SessionController::class, 'logout'])
    ->middleware('auth:web')
    ->name('logout');