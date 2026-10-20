<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminPoliController;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\PasienController;
use App\Http\Controllers\Admin\ObatController;
use App\Http\Controllers\Dokter\JadwalPeriksaController;
use App\Http\Controllers\Dokter\PeriksaPasienController;
use App\Http\Controllers\Pasien\PoliController as PasienPoliController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\Admin\ExportController as AdminExportController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\Dokter\ExportController as DokterExportController;
use App\Http\Controllers\Pasien\DashboardController as PasienDashboardController;
use App\Http\Controllers\Pasien\RiwayatDaftarController;
use App\Http\Controllers\Pasien\PembayaranController as PasienPembayaranController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dokter\RiwayatPasienController;

Route::get('/', function () {
    return view('welcome');
});

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Semua role yang sudah login: profile (ganti password) & notifikasi
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/{id}/baca', [NotifikasiController::class, 'baca'])->name('notifikasi.baca');
});

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/export/dokter', [AdminExportController::class, 'dokter'])->name('export.dokter');
    Route::get('/export/pasien', [AdminExportController::class, 'pasien'])->name('export.pasien');
    Route::get('/export/obat', [AdminExportController::class, 'obat'])->name('export.obat');

    Route::get('/pembayaran', [AdminPembayaranController::class, 'index'])->name('admin.pembayaran.index');
    Route::post('/pembayaran/{id}/konfirmasi', [AdminPembayaranController::class, 'konfirmasi'])->name('admin.pembayaran.konfirmasi');

    Route::resource('polis', AdminPoliController::class);
    Route::resource('dokter', DokterController::class);
    Route::resource('pasien', PasienController::class);
    Route::resource('obat', ObatController::class);
});

// Dokter
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {
    Route::get('/dashboard', function () {
        return view('dokter.dashboard');
    })->name('dokter.dashboard');

    Route::get('/export/jadwal-periksa', [DokterExportController::class, 'jadwal'])->name('export.jadwal');
    Route::get('/export/riwayat-pasien', [DokterExportController::class, 'riwayat'])->name('export.riwayat');

    Route::resource('jadwal-periksa', JadwalPeriksaController::class);

    Route::get('/periksa-pasien', [PeriksaPasienController::class, 'index'])->name('periksa-pasien.index');
    Route::post('/periksa-pasien', [PeriksaPasienController::class, 'store'])->name('periksa-pasien.store');
    Route::get('/periksa-pasien/{id}', [PeriksaPasienController::class, 'create'])->name('periksa-pasien.create');

    Route::get('/riwayat-pasien', [RiwayatPasienController::class, 'index'])->name('riwayat-pasien.index');
    Route::get('/riwayat-pasien/{id}', [RiwayatPasienController::class, 'show'])->name('riwayat-pasien.show');
});

// Pasien
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {
    Route::get('/dashboard', [PasienDashboardController::class, 'index'])->name('pasien.dashboard');

    Route::get('/daftar', [PasienPoliController::class, 'get'])->name('pasien.daftar');

    Route::post('/daftar', [PasienPoliController::class, 'submit'])
        ->name('pasien.daftar.submit');

    Route::get('/riwayat-pendaftaran', [RiwayatDaftarController::class, 'index'])->name('pasien.riwayat.index');
    Route::get('/riwayat-pendaftaran/{id}', [RiwayatDaftarController::class, 'show'])->name('pasien.riwayat.show');

    Route::get('/pembayaran', [PasienPembayaranController::class, 'index'])->name('pasien.pembayaran.index');
    Route::post('/pembayaran/{id}', [PasienPembayaranController::class, 'store'])->name('pasien.pembayaran.store');
});
