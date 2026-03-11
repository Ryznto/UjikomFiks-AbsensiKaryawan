<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Auth
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── Admin ─────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role.admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Profil
    Route::get('profil', [App\Http\Controllers\Admin\ProfilController::class, 'index'])->name('profil.index');
    Route::put('profil', [App\Http\Controllers\Admin\ProfilController::class, 'update'])->name('profil.update');
    Route::put('profil/password', [App\Http\Controllers\Admin\ProfilController::class, 'updatePassword'])->name('profil.password');

    // Karyawan
    Route::resource('karyawan', App\Http\Controllers\Admin\KaryawanController::class);
    Route::post('karyawan/{karyawan}/reset-password', [App\Http\Controllers\Admin\KaryawanController::class, 'resetPassword'])->name('karyawan.reset-password');

    // Presensi
    Route::get('presensi', [App\Http\Controllers\Admin\PresensiController::class, 'index'])->name('presensi.index');

    // Izin Cuti
    Route::get('izin-cuti', [App\Http\Controllers\Admin\IzinCutiController::class, 'index'])->name('izin-cuti.index');
    Route::patch('izin-cuti/{izinCuti}/approve', [App\Http\Controllers\Admin\IzinCutiController::class, 'approve'])->name('izin-cuti.approve');
    Route::patch('izin-cuti/{izinCuti}/reject', [App\Http\Controllers\Admin\IzinCutiController::class, 'reject'])->name('izin-cuti.reject');

    // Master Data
    Route::resource('divisi', App\Http\Controllers\Admin\DivisiController::class);
    Route::resource('jabatan', App\Http\Controllers\Admin\JabatanController::class);
    Route::resource('shift', App\Http\Controllers\Admin\ShiftController::class);

    // Koreksi Absen
    Route::get('koreksi-absen', [App\Http\Controllers\Admin\KoreksiAbsenController::class, 'index'])->name('koreksi-absen.index');
    Route::patch('koreksi-absen/{koreksiAbsen}/approve', [App\Http\Controllers\Admin\KoreksiAbsenController::class, 'approve'])->name('koreksi-absen.approve');
    Route::patch('koreksi-absen/{koreksiAbsen}/reject', [App\Http\Controllers\Admin\KoreksiAbsenController::class, 'reject'])->name('koreksi-absen.reject');

    // Laporan
    Route::get('laporan', [App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/presensi/excel', [App\Http\Controllers\Admin\LaporanController::class, 'presensiExcel'])->name('laporan.presensi.excel');
    Route::get('laporan/presensi/pdf', [App\Http\Controllers\Admin\LaporanController::class, 'presensiPdf'])->name('laporan.presensi.pdf');
    Route::get('laporan/karyawan/excel', [App\Http\Controllers\Admin\LaporanController::class, 'karyawanExcel'])->name('laporan.karyawan.excel');
    Route::get('laporan/karyawan/pdf', [App\Http\Controllers\Admin\LaporanController::class, 'karyawanPdf'])->name('laporan.karyawan.pdf');
    Route::get('laporan/izin-cuti/excel', [App\Http\Controllers\Admin\LaporanController::class, 'izinCutiExcel'])->name('laporan.izin-cuti.excel');
    Route::get('laporan/izin-cuti/pdf', [App\Http\Controllers\Admin\LaporanController::class, 'izinCutiPdf'])->name('laporan.izin-cuti.pdf');

    // ── Kategori Penilaian ────────────────────────────
    Route::get('assessment-categories', [App\Http\Controllers\Admin\AssessmentCategoryController::class, 'index'])->name('assessment-categories.index');
    Route::post('assessment-categories', [App\Http\Controllers\Admin\AssessmentCategoryController::class, 'store'])->name('assessment-categories.store');
    Route::put('assessment-categories/{assessmentCategory}', [App\Http\Controllers\Admin\AssessmentCategoryController::class, 'update'])->name('assessment-categories.update');
    Route::patch('assessment-categories/{assessmentCategory}/toggle', [App\Http\Controllers\Admin\AssessmentCategoryController::class, 'toggleStatus'])->name('assessment-categories.toggle');
    Route::delete('assessment-categories/{assessmentCategory}', [App\Http\Controllers\Admin\AssessmentCategoryController::class, 'destroy'])->name('assessment-categories.destroy');

    // ── Penilaian ─────────────────────────────────────
    Route::get('assessments', [App\Http\Controllers\Admin\AssessmentController::class, 'index'])->name('assessments.index');
    Route::get('assessments/report', [App\Http\Controllers\Admin\AssessmentController::class, 'report'])->name('assessments.report');
    Route::get('assessments/create/{karyawan}', [App\Http\Controllers\Admin\AssessmentController::class, 'create'])->name('assessments.create');
    Route::post('assessments', [App\Http\Controllers\Admin\AssessmentController::class, 'store'])->name('assessments.store');
    Route::get('assessments/{assessment}', [App\Http\Controllers\Admin\AssessmentController::class, 'show'])->name('assessments.show');
});

// ── Karyawan ──────────────────────────────────────────────────────
Route::prefix('karyawan')->name('karyawan.')->middleware(['auth', 'role.karyawan'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Karyawan\DashboardController::class, 'index'])->name('dashboard');
    Route::get('presensi', [App\Http\Controllers\Karyawan\PresensiController::class, 'index'])->name('presensi.index');
    Route::post('presensi/masuk', [App\Http\Controllers\Karyawan\PresensiController::class, 'absenMasuk'])->name('presensi.masuk');
    Route::post('presensi/pulang', [App\Http\Controllers\Karyawan\PresensiController::class, 'absenPulang'])->name('presensi.pulang');
    Route::get('izin-cuti', [App\Http\Controllers\Karyawan\IzinCutiController::class, 'index'])->name('izin-cuti.index');
    Route::post('izin-cuti', [App\Http\Controllers\Karyawan\IzinCutiController::class, 'store'])->name('izin-cuti.store');
    Route::get('profil', [App\Http\Controllers\Karyawan\ProfilController::class, 'index'])->name('profil');
    Route::put('profil/password', [App\Http\Controllers\Karyawan\ProfilController::class, 'updatePassword'])->name('profil.password');
    Route::get('koreksi-absen', [App\Http\Controllers\Karyawan\KoreksiAbsenController::class, 'index'])->name('koreksi-absen.index');
    Route::get('koreksi-absen/create', [App\Http\Controllers\Karyawan\KoreksiAbsenController::class, 'create'])->name('koreksi-absen.create');
    Route::post('koreksi-absen', [App\Http\Controllers\Karyawan\KoreksiAbsenController::class, 'store'])->name('koreksi-absen.store');

    // ── Rapor Penilaian ───────────────────────────────
    Route::get('my-report', [App\Http\Controllers\Karyawan\AssessmentController::class, 'myReport'])->name('assessments.my-report');
    Route::get('my-report/{assessment}', [App\Http\Controllers\Karyawan\AssessmentController::class, 'show'])->name('assessments.show');
});