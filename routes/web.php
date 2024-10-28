<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PemesananController;

/*
|-----------------------------------------------------------------------
| Web Routes
|-----------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect the homepage to the login page if not authenticated
Route::get('/', function () {
    return redirect()->route('login');
});

// Show login form
Route::get('/login', [PemesananController::class, 'showLoginForm'])->name('login');
Route::post('/login', [PemesananController::class, 'login']);

// Registration routes
Route::get('/register', [PemesananController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [PemesananController::class, 'register']);

// Protecting routes - Only accessible to authenticated users
Route::middleware('auth')->group(function () {
    // Dashboard route
    Route::get('/home', [PemesananController::class, 'index'])->name('index');

    Route::get('/create', [PemesananController::class, 'create'])->name('create');
    Route::post('/store', [PemesananController::class, 'store'])->name('store');

    // Routes for managing orders
    Route::resource('pemesanan', PemesananController::class)->except(['show']); // This will create all the RESTful routes needed
    Route::post('/pemesanan/{id}/complete', [PemesananController::class, 'markAsCompleted'])->name('mark-as-completed');
//
Route::get('/pemesanan', [PemesananController::class, 'index'])->name('index');
Route::put('/pemesanan/{id}/updateStatus', [PemesananController::class, 'updateStatus'])->name('pemesanan.updateStatus');


Route::get('/history', [PemesananController::class, 'history'])->name('history');

// live server
Route::get('/orders/latest', [PemesananController::class, 'getLatestOrders'])->name('orders.latest');

    // Logout route
    Route::post('/logout', [PemesananController::class, 'logout'])->name('logout');
});
