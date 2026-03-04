<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\Karyawan;
use App\Models\Divisi;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Presensi::with(['karyawan.user', 'karyawan.divisi', 'karyawan.shift']);

        // Filter tanggal
        if ($request->tanggal) {
            $query->whereDate('tanggal', $request->tanggal);
        } else {
            $query->whereDate('tanggal', today());
        }

        // Filter divisi
        if ($request->divisi_id) {
            $query->whereHas('karyawan', fn($q) => $q->where('divisi_id', $request->divisi_id));
        }

        // Filter status
        if ($request->status_absen) {
            $query->where('status_absen', $request->status_absen);
        }

        $presensis = $query->latest()->paginate(15)->withQueryString();
        $divisis   = Divisi::all();

        // Summary
        $tanggal        = $request->tanggal ?? today()->toDateString();
        $totalHadir     = Presensi::whereDate('tanggal', $tanggal)->whereNotNull('jam_masuk')->count();
        $totalTerlambat = Presensi::whereDate('tanggal', $tanggal)->where('status_absen', 'terlambat')->count();
        $totalAlfa      = Presensi::whereDate('tanggal', $tanggal)->where('status_absen', 'alfa')->count();
        $totalPulangCepat = Presensi::whereDate('tanggal', $tanggal)->where('status_absen', 'pulang_cepat')->count();

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