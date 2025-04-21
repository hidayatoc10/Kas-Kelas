<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BendaharaController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [BendaharaController::class, 'cek_siswa'])->name('home');
    Route::post('/', [BendaharaController::class, 'proses_cek_siswa'])->name('cek.nis');
    Route::post('/cetak', [BendaharaController::class, 'cetak_bukti'])->name('cetak.bukti');
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login_post');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [BendaharaController::class, 'index'])->name('dashboard');
    Route::get('/akun_saya', [BendaharaController::class, 'editAccount'])->name('updateAccount');
    Route::post('/akun_saya', [BendaharaController::class, 'editAccountPost'])->name('updateAccount_post');
    Route::get('/siswa', [BendaharaController::class, 'siswa'])->name('siswa');
    Route::get('/tambah', [BendaharaController::class, 'siswaAdd'])->name('siswaAdd');
    Route::post('/tambah', [BendaharaController::class, 'siswaAddPost'])->name('siswaAdd_post');
    Route::get('/edit/{nis}', [BendaharaController::class, 'siswaEdit'])->name('siswaEdit');
    Route::post('/edit/{nis}', [BendaharaController::class, 'siswaEditPost'])->name('siswaEdit_post');
    Route::get('/hapus/{nis}', [BendaharaController::class, 'siswaDelete'])->name('siswaDelete');
    Route::post('/siswa/import', [BendaharaController::class, 'importSiswa'])->name('siswaImport');
    Route::get('/pemasukan', [BendaharaController::class, 'pemasukan'])->name('pemasukan');
    Route::get('catat_kas', [BendaharaController::class, 'pemasukanAdd'])->name('pemasukanAdd');
    Route::post('catat_kas', [BendaharaController::class, 'pemasukanAddPost'])->name('pemasukanAdd_post');
    Route::get('hapuss/{id}', [BendaharaController::class, 'pemasukanDelete'])->name('pemasukanDelete');
    Route::post('tambah_bulan', [BendaharaController::class, 'AddBulan'])->name('AddBulan');
    Route::get('hapus_bulan/{id}', [BendaharaController::class, 'DeleteBulan'])->name('DeleteBulan');
    Route::get('/catat-kas/{bulan_id}', [BendaharaController::class, 'show'])->name('catat.kas');
    Route::post('/catat-kas/update', [BendaharaController::class, 'updateMinggu'])->name('catat.kas.update');
    Route::get('/laporan/pemasukan', [BendaharaController::class, 'laporan'])->name('laporan');
    Route::post('/laporan/pemasukan', [BendaharaController::class, 'tampilkan'])->name('laporan.pemasukan.tampilkan');
    Route::get('/pengeluaran', [BendaharaController::class, 'pengeluaran'])->name('pengeluaran');
    Route::post('/pengeluaran/tambah', [BendaharaController::class, 'pengeluaranAdd'])->name('pengeluaranAdd');
    Route::post('/pengeluaran/update/{id}', [BendaharaController::class, 'pengeluaranEditPost'])->name('pengeluaranUpdate');
    Route::get('/pengeluaran/hapus/{id}', [BendaharaController::class, 'pengeluaranDelete'])->name('pengeluaranDelete');
    Route::get('/laporan_pengeluaran', [BendaharaController::class, 'laporanPengeluaran'])->name('laporan.pengeluaran');
    Route::post('/laporan_pengeluaran', [BendaharaController::class, 'laporanPengeluaranTampilkan'])->name('laporan.pengeluaran.tampilkan');
});
