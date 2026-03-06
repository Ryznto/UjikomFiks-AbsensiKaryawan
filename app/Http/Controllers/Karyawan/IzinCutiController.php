<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\IzinCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Presensi;
class IzinCutiController extends Controller
{
   public function index(Request $request)
{
    $karyawan = Auth::user()->karyawan;

    $query = IzinCuti::where('karyawan_id', $karyawan->id);

    if ($request->bulan) {
        $query->whereMonth('tanggal_mulai', $request->bulan);
    }

    if ($request->tahun) {
        $query->whereYear('tanggal_mulai', $request->tahun);
    }

    if ($request->jenis) {
        $query->where('jenis', $request->jenis);
    }

    if ($request->status) {
        $query->where('status', $request->status);
    }

    $izinCutis = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

    return view('karyawan.izin-cuti.index', compact('izinCutis'));
}

   public function store(Request $request)
{
    $request->validate([
        'jenis'           => 'required|in:izin,sakit,cuti',
        'tanggal_mulai'   => 'required|date|after_or_equal:today',
        'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        'keterangan'      => 'nullable|string|max:500',
    ]);

    $karyawan = Auth::user()->karyawan;

    // Cek apakah sudah absen di salah satu tanggal yang diajukan
    $sudahAbsen = Presensi::where('karyawan_id', $karyawan->id)
        ->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai])
        ->whereNotNull('jam_masuk')
        ->first();

    if ($sudahAbsen) {
        return back()->withErrors([
            'tanggal_mulai' => 'Kamu sudah absen pada tanggal ' . \Carbon\Carbon::parse($sudahAbsen->tanggal)->format('d M Y') . ', tidak bisa mengajukan izin/cuti di tanggal tersebut.'
        ])->withInput();
    }

    // Cek apakah sudah ada pengajuan di tanggal yang sama
    $exists = IzinCuti::where('karyawan_id', $karyawan->id)
        ->where('status', '!=', 'rejected')
        ->where(function ($q) use ($request) {
            $q->whereBetween('tanggal_mulai', [$request->tanggal_mulai, $request->tanggal_selesai])
              ->orWhereBetween('tanggal_selesai', [$request->tanggal_mulai, $request->tanggal_selesai]);
        })->exists();

    if ($exists) {
        return back()->withErrors([
            'tanggal_mulai' => 'Sudah ada pengajuan izin/cuti di tanggal tersebut.'
        ])->withInput();
    }

    IzinCuti::create([
        'karyawan_id'     => $karyawan->id,
        'jenis'           => $request->jenis,
        'tanggal_mulai'   => $request->tanggal_mulai,
        'tanggal_selesai' => $request->tanggal_selesai,
        'keterangan'      => $request->keterangan,
        'status'          => 'pending',
    ]);

    return redirect()->route('karyawan.izin-cuti.index')
        ->with('success', 'Pengajuan berhasil dikirim, menunggu persetujuan admin.');
}
}