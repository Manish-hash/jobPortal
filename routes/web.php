<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [HomeController::class, 'index'])->name('home.index');



Route::prefix('account')->group(function () {
    // Guest Routes
    Route::middleware('guest')->group(function () {
        Route::get('/registration', [AccountController::class, 'registration'])->name('account.register');
        Route::post('/save-registration', [AccountController::class, 'saveRegistration'])->name('account.save-registration');
        Route::get('/login', [AccountController::class, 'login'])->name('account.login');
        Route::post('/auth', [AccountController::class, 'authenticate'])->name('account.auth');
    });

    // Authenticated Routes
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::post('/logout', [AccountController::class, 'logout'])->name('account.logout');
    });
});
