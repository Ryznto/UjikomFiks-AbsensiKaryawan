<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Presensi;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today()->toDateString();

        $totalKaryawan    = Karyawan::where('status_aktif', true)->count();
        $hadirHariIni     = Presensi::whereDate('tanggal', $today)->whereNotNull('jam_masuk')->count();
        $terlambatHariIni = Presensi::whereDate('tanggal', $today)->where('status_absen', 'terlambat')->count();
        $alfaHariIni      = Presensi::whereDate('tanggal', $today)->where('status_absen', 'alfa')->count();

        $presensiHariIni  = Presensi::with(['karyawan.divisi', 'karyawan.shift'])
            ->whereDate('tanggal', $today)
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalKaryawan',
            'hadirHariIni',
            'terlambatHariIni',
            'alfaHariIni',
            'presensiHariIni'
        ));
    }
}