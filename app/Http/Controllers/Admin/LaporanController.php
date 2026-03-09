<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\PresensiExport;
use App\Exports\KaryawanRekapExport;
use App\Exports\IzinCutiExport;
use App\Models\Presensi;
use App\Models\Karyawan;
use App\Models\IzinCuti;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan   = $request->bulan   ?? date('n');
        $tahun   = $request->tahun   ?? date('Y');
        $divisiId = $request->divisi_id;
        $divisis = Divisi::all();

        // Rekap presensi
        $totalHadir = Presensi::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->whereNotNull('jam_masuk')
            ->where('status_absen', '!=', 'alfa')
            ->when($divisiId, fn($q) => $q->whereHas('karyawan', fn($q) => $q->where('divisi_id', $divisiId)))
            ->count();

        $totalTerlambat = Presensi::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('status_absen', 'terlambat')
            ->when($divisiId, fn($q) => $q->whereHas('karyawan', fn($q) => $q->where('divisi_id', $divisiId)))
            ->count();

        $totalAlfa = Presensi::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('status_absen', 'alfa')
            ->when($divisiId, fn($q) => $q->whereHas('karyawan', fn($q) => $q->where('divisi_id', $divisiId)))
            ->count();

        $totalIzinCuti = IzinCuti::whereMonth('tanggal_mulai', $bulan)
            ->whereYear('tanggal_mulai', $tahun)
            ->where('status', 'approved')
            ->when($divisiId, fn($q) => $q->whereHas('karyawan', fn($q) => $q->where('divisi_id', $divisiId)))
            ->count();

        return view('admin.laporan.index', compact(
            'bulan', 'tahun', 'divisis', 'divisiId',
            'totalHadir', 'totalTerlambat', 'totalAlfa', 'totalIzinCuti'
        ));
    }

    // ── EXCEL ──
    public function presensiExcel(Request $request)
    {
        $bulan  = $request->bulan  ?? date('n');
        $tahun  = $request->tahun  ?? date('Y');
        $nama   = 'laporan-presensi-' . Carbon::create($tahun, $bulan)->format('F-Y') . '.xlsx';
        return Excel::download(new PresensiExport($bulan, $tahun, $request->divisi_id), $nama);
    }

    public function karyawanExcel(Request $request)
    {
        $bulan  = $request->bulan  ?? date('n');
        $tahun  = $request->tahun  ?? date('Y');
        $nama   = 'rekap-karyawan-' . Carbon::create($tahun, $bulan)->format('F-Y') . '.xlsx';
        return Excel::download(new KaryawanRekapExport($bulan, $tahun, $request->divisi_id), $nama);
    }

    public function izinCutiExcel(Request $request)
    {
        $bulan  = $request->bulan  ?? date('n');
        $tahun  = $request->tahun  ?? date('Y');
        $nama   = 'laporan-izin-cuti-' . Carbon::create($tahun, $bulan)->format('F-Y') . '.xlsx';
        return Excel::download(new IzinCutiExport($bulan, $tahun, $request->divisi_id), $nama);
    }

    // ── PDF ──
    public function presensiPdf(Request $request)
    {
        $bulan    = $request->bulan  ?? date('n');
        $tahun    = $request->tahun  ?? date('Y');
        $divisiId = $request->divisi_id;

        $presensis = Presensi::with(['karyawan.user', 'karyawan.divisi', 'karyawan.shift'])
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->when($divisiId, fn($q) => $q->whereHas('karyawan', fn($q) => $q->where('divisi_id', $divisiId)))
            ->orderBy('tanggal')
            ->get();

        $judul  = 'Laporan Presensi ' . Carbon::create($tahun, $bulan)->translatedFormat('F Y');
        $pdf    = Pdf::loadView('admin.laporan.pdf.presensi', compact('presensis', 'judul', 'bulan', 'tahun'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-presensi-' . Carbon::create($tahun, $bulan)->format('F-Y') . '.pdf');
    }

    public function karyawanPdf(Request $request)
    {
        $bulan    = $request->bulan  ?? date('n');
        $tahun    = $request->tahun  ?? date('Y');
        $divisiId = $request->divisi_id;

        $karyawans = Karyawan::with(['user', 'divisi', 'jabatan', 'shift'])
            ->where('status_aktif', true)
            ->when($divisiId, fn($q) => $q->where('divisi_id', $divisiId))
            ->orderBy('nama')
            ->get();

        $judul = 'Rekap Karyawan ' . Carbon::create($tahun, $bulan)->translatedFormat('F Y');
        $pdf   = Pdf::loadView('admin.laporan.pdf.karyawan', compact('karyawans', 'judul', 'bulan', 'tahun'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('rekap-karyawan-' . Carbon::create($tahun, $bulan)->format('F-Y') . '.pdf');
    }

    public function izinCutiPdf(Request $request)
    {
        $bulan    = $request->bulan  ?? date('n');
        $tahun    = $request->tahun  ?? date('Y');
        $divisiId = $request->divisi_id;

        $izinCutis = IzinCuti::with(['karyawan.user', 'karyawan.divisi'])
            ->whereMonth('tanggal_mulai', $bulan)
            ->whereYear('tanggal_mulai', $tahun)
            ->when($divisiId, fn($q) => $q->whereHas('karyawan', fn($q) => $q->where('divisi_id', $divisiId)))
            ->orderBy('tanggal_mulai')
            ->get();

        $judul = 'Laporan Izin & Cuti ' . Carbon::create($tahun, $bulan)->translatedFormat('F Y');
        $pdf   = Pdf::loadView('admin.laporan.pdf.izin-cuti', compact('izinCutis', 'judul', 'bulan', 'tahun'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-izin-cuti-' . Carbon::create($tahun, $bulan)->format('F-Y') . '.pdf');
    }
}