<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\Karyawan;
use App\Models\Divisi;
use Illuminate\Http\Request;

// [INHERITANCE] = PresensiController mewarisi fitur dari Controller Laravel
class PresensiController extends Controller
{
    // [METHOD] = fungsi untuk menampilkan halaman daftar presensi
    public function index(Request $request)
    {
        // [OBJECT] = $request adalah object dari class Request
        // [VARIABLE] = $query menyimpan query builder presensi beserta relasi nya
        $query = Presensi::with(['karyawan.user', 'karyawan.divisi', 'karyawan.shift']);

        // cek apakah user mengirim filter tanggal atau tidak
        if ($request->tanggal) {
            $query->whereDate('tanggal', $request->tanggal);
        } else {
            $query->whereDate('tanggal', today());
        }

        // [KONDISI] = cek apakah user mengirim filter divisi atau tidak
        if ($request->divisi_id) {
            $query->whereHas('karyawan', fn($q) => $q->where('divisi_id', $request->divisi_id));
        }

        // [KONDISI] = cek apakah user mengirim filter status absen atau tidak
        if ($request->status_absen) {
            $query->where('status_absen', $request->status_absen);
        }

        // [VARIABLE] = hasil query diurutkan terbaru, dipaginasi 15 data per halaman
        $presensis = $query->latest()->paginate(15)->withQueryString();

        // [VARIABLE + OBJECT] = ambil semua data divisi untuk dropdown filter
        $divisis   = Divisi::all();

        // [VARIABLE] = ?? artinya jika tanggal kosong/null, pakai tanggal hari ini
        $tanggal          = $request->tanggal ?? today()->toDateString();

        // [VARIABLE] = hitung total karyawan hadir, terlambat, alfa, pulang cepat
        $totalHadir       = Presensi::whereDate('tanggal', $tanggal)->whereNotNull('jam_masuk')->count();
        $totalTerlambat   = Presensi::whereDate('tanggal', $tanggal)->where('status_absen', 'terlambat')->count();
        $totalAlfa        = Presensi::whereDate('tanggal', $tanggal)->where('status_absen', 'alfa')->count();
        $totalPulangCepat = Presensi::whereDate('tanggal', $tanggal)->where('status_absen', 'pulang_cepat')->count();

        // [RETURN] = kirim semua variable ke view menggunakan compact()
        return view('admin.presensi.index', compact(
            'presensis',
            'divisis',
            'totalHadir',
            'totalTerlambat',
            'totalAlfa',
            'totalPulangCepat',
            'tanggal'
        ));
    }
}