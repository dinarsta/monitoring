<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PemesananController;

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
    return view('home');
});


Route::get('/', [PemesananController::class, 'index'])->name('index');
Route::get('/create', [PemesananController::class, 'create'])->name('create');
Route::post('/store', [PemesananController::class, 'store'])->name('store');


// Show the form for editing an order
Route::get('/pemesanans/{id}/edit', [PemesananController::class, 'edit'])->name('edit');

// Handle form submission for updating an order
Route::post('/pemesanans/{id}/edit', [PemesananController::class, 'update']);

Route::get('/pemesanan/{id}/edit', [PemesananController::class, 'edit'])->name('edit');

Route::post('/pemesanan/{id}/complete', [PemesananController::class, 'markAsCompleted'])->name('mark-as-completed');


Route::delete('/pemesanan/{id}', [PemesananController::class, 'destroy'])->name('delete');


