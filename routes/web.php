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


// Display all orders
Route::get('/index', [PemesananController::class, 'index'])->name('index');

// Show the form for creating a new order
Route::get('create', [PemesananController::class, 'create'])->name('create');

// Handle form submission for creating a new order
Route::post('create', [PemesananController::class, 'store']);

// Show the details of a specific order
Route::get('/pemesanans/{id}', [PemesananController::class, 'show'])->name('show');

// Show the form for editing an order
Route::get('/pemesanans/{id}/edit', [PemesananController::class, 'edit'])->name('edit');

// Handle form submission for updating an order
Route::post('/pemesanans/{id}/edit', [PemesananController::class, 'update']);

// Handle deletion of an order
Route::post('/pemesanans/{id}/delete', [PemesananController::class, 'destroy'])->name('destroy');
