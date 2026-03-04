<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\IzinCuti;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $karyawan = Auth::user()->karyawan;
        $today    = today()->toDateString();

        $presensiHariIni = Presensi::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $today)
            ->first();

        $riwayatPresensi = Presensi::where('karyawan_id', $karyawan->id)
            ->orderByDesc('tanggal')
            ->take(5)
            ->get();

        $izinPending = IzinCuti::where('karyawan_id', $karyawan->id)
            ->where('status', 'pending')
            ->count();

        // Hitung kehadiran bulan ini
        $hadirBulanIni = Presensi::where('karyawan_id', $karyawan->id)
            ->whereMonth('tanggal', today()->month)
            ->whereYear('tanggal', today()->year)
            ->whereNotNull('jam_masuk')
            ->count();

        $terlambatBulanIni = Presensi::where('karyawan_id', $karyawan->id)
            ->whereMonth('tanggal', today()->month)
            ->whereYear('tanggal', today()->year)
            ->where('status_absen', 'terlambat')
            ->count();

        return view('karyawan.dashboard', compact(
            'karyawan',
            'presensiHariIni',
            'riwayatPresensi',
            'izinPending',
            'hadirBulanIni',
            'terlambatBulanIni'
        ));
    }
}