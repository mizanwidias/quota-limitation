<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\KuotaController;
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

Route::get('/welcome', function () {
    return view('welcome');
});

Route::resource('/', HomeController::class)->name('index', 'home');
Route::get('/api/top-services', [HomeController::class, 'getTopServices'])->name('home.top-services');
Route::resource('paket-kuota', KuotaController::class)->name('index', 'kuota');
Route::get('/paket-kuota/{id}/pilih', [KuotaController::class, 'pilih'])->name('kuota.pilih');
