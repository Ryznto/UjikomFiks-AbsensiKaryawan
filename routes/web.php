    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Auth\LoginController;
    // Auth
    Route::get('/', fn() => redirect()->route('login'));
    Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
    Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// Admin
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'role.admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

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
});

// Karyawan
Route::prefix('karyawan')->name('karyawan.')->middleware(['auth', 'role.karyawan'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Karyawan\DashboardController::class, 'index'])->name('dashboard');
    Route::get('presensi', [App\Http\Controllers\Karyawan\PresensiController::class, 'index'])->name('presensi.index');
    Route::post('presensi/masuk', [App\Http\Controllers\Karyawan\PresensiController::class, 'absenMasuk'])->name('presensi.masuk');
    Route::post('presensi/pulang', [App\Http\Controllers\Karyawan\PresensiController::class, 'absenPulang'])->name('presensi.pulang');
    Route::get('izin-cuti', [App\Http\Controllers\Karyawan\IzinCutiController::class, 'index'])->name('izin-cuti.index');
    Route::post('izin-cuti', [App\Http\Controllers\Karyawan\IzinCutiController::class, 'store'])->name('izin-cuti.store');
    Route::get('profil', [App\Http\Controllers\Karyawan\ProfilController::class, 'index'])->name('profil');
Route::put('profil/password', [App\Http\Controllers\Karyawan\ProfilController::class, 'updatePassword'])->name('profil.password');
    });

