<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\KoreksiAbsen;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KoreksiAbsenController extends Controller
{
    public function index()
    {
        $karyawan  = Auth::user()->karyawan;
        $koreksis  = KoreksiAbsen::where('karyawan_id', $karyawan->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('karyawan.koreksi-absen.index', compact('koreksis'));
    }

    public function create()
{
    $karyawan = Auth::user()->karyawan;

    // Ambil presensi alfa + hari yang belum ada koreksi pending/approved
    $alfas = Presensi::where('karyawan_id', $karyawan->id)
        ->where('status_absen', 'alfa')
        ->whereNotIn('id', function($q) use ($karyawan) {
            $q->select('presensi_id')
              ->from('koreksi_absen')
              ->where('karyawan_id', $karyawan->id)
              ->whereIn('status', ['pending', 'approved'])
              ->whereNotNull('presensi_id');
        })
        ->orderByDesc('tanggal')
        ->get();

    return view('karyawan.koreksi-absen.create', compact('alfas'));
}

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'    => 'required|date|before_or_equal:today',
            'jam_masuk'  => 'required',
            'jam_pulang' => 'nullable|after:jam_masuk',
            'alasan'     => 'required|string|max:500',
        ]);
        // Validasi Sabtu/Minggu
        $hari = \Carbon\Carbon::parse($request->tanggal)->dayOfWeek;
        if ($hari === 0 || $hari === 6) {
            return back()->withErrors([
                'tanggal' => 'Tidak bisa mengajukan koreksi di hari ' . \Carbon\Carbon::parse($request->tanggal)->translatedFormat('l') . ' (hari libur).'
            ])->withInput();
        }
        $karyawan = Auth::user()->karyawan;

        // Cek apakah sudah ada koreksi pending di tanggal yang sama
        $exists = KoreksiAbsen::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $request->tanggal)
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'tanggal' => 'Sudah ada pengajuan koreksi pending di tanggal tersebut.'
            ])->withInput();
        }

        // Cari presensi yang ada di tanggal itu (kalau ada)
        $presensi = Presensi::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $request->tanggal)
            ->first();

        KoreksiAbsen::create([
            'karyawan_id' => $karyawan->id,
            'presensi_id' => $presensi?->id,
            'tanggal'     => $request->tanggal,
            'jam_masuk'   => $request->jam_masuk,
            'jam_pulang'  => $request->jam_pulang,
            'alasan'      => $request->alasan,
            'status'      => 'pending',
        ]);

        return redirect()->route('karyawan.koreksi-absen.index')
            ->with('success', 'Pengajuan koreksi berhasil dikirim.');
    }
}