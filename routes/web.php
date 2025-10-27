<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\KuotaController;
use App\Http\Controllers\LoginUserController;
use Illuminate\Support\Facades\Route;
use Tinderbox\ClickhouseBuilder\Integrations\Laravel\Connection;


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
    return redirect()->route('login_page'); // biar root langsung redirect ke home
});

// 👇 semua route yang butuh login bungkus dengan middleware('auth')
Route::middleware(['auth'])->group(function () {
    Route::resource('/home', HomeController::class)
        ->name('index', 'home');
    Route::get('/active-hosts', [HomeController::class, 'getActiveHosts'])->name('active-hosts');
    Route::get('/api/top-services', [HomeController::class, 'getTopServices'])->name('home.top-services');
    Route::resource('paket-kuota', KuotaController::class)->name('index', 'kuota');
    Route::get('/paket-kuota/{id}/pilih', [KuotaController::class, 'pilih'])->name('kuota.pilih');
});

// MIDDLEWARE LOGIN BUAT PELANGGAN
Route::middleware(['guest'])->group(function () {
    Route::get('/login-user', [LoginUserController::class, 'login_page'])
        ->name('login_page');
    Route::post('/login_proses', [LoginUserController::class, 'login_proses'])->name('login_proses');
});
Route::get('/logout', [\App\Http\Controllers\LoginUserController::class, 'logout'])->name('logout');

// redirect semua yang mulai dengan "home" ke route 'home-user'
Route::get('/home{any?}', function () {
    return redirect()->route('home-user');
})->where('any', '.*');

// redirect semua yang mulai dengan "login" ke route 'login_page'
Route::get('/login{any?}', function () {
    return redirect()->route('login_page');
})->where('any', '.*');
