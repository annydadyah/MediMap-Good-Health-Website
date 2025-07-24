<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HealthFacilityController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------|
| Web Routes                                                               |
|--------------------------------------------------------------------------|
*/

// --- SEMUA RUTE LAMA ANDA TETAP DI SINI, TIDAK DIUBAH ---

// Halaman utama
Route::get('/', fn() => Auth::check() ? redirect()->route('dashboard.index') : redirect()->route('login'))->name('home');

// --- RUTE AUTENTIKASI ---

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// ... (dan seterusnya)

// --- RUTE PROFIL PENGGUNA ---
Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::post('/update', [ProfileController::class, 'update'])->name('update');
});

// --- RUTE UTAMA APLIKASI ---
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

// --- RUTE UNTUK HEALTH FACILITIES (FASILITAS KESEHATAN) ---
Route::resource('health-facilities', HealthFacilityController::class)
    ->parameters(['health-facilities' => 'hospital'])
    ->names('healthfacilities');

// --- RUTE UNTUK REVIEW ---

// Halaman utama pencarian review
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');

// Rute untuk menyimpan review baru
Route::post('/health-facilities/{hospital}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

// =================== RUTE BARU DITAMBAHKAN DI SINI ===================
/**
 * Rute baru ini khusus untuk menampilkan halaman detail rumah sakit
 * beserta semua review-nya.
 * URL: /hospitals/{hospital}/details
 * Ditangani oleh: HealthFacilityController@showDetailsAndReviews
 * Nama Rute: hospitals.showDetails
 */
Route::get('/hospitals/{hospital}/details', [HealthFacilityController::class, 'showDetailsAndReviews'])
    ->name('hospitals.showDetails');
// =================== END OF RUTE BARU ===================

Route::get('/reviews/{hospital}', [ReviewController::class, 'showDetails'])->name('reviews.showDetails');

Route::get('/reviews/{hospital}/create', [ReviewController::class, 'create'])->name('reviews.create');