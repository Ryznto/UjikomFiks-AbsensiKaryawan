<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KoreksiAbsen;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KoreksiAbsenController extends Controller
{
    public function index()
    {
        $koreksis     = KoreksiAbsen::with(['karyawan.user', 'karyawan.divisi', 'approvedBy.adminProfile'])
            ->orderByDesc('created_at')
            ->paginate(15);

        $totalPending = KoreksiAbsen::where('status', 'pending')->count();

        return view('admin.koreksi-absen.index', compact('koreksis', 'totalPending'));
    }

    public function approve(Request $request, KoreksiAbsen $koreksiAbsen)
    {
        $request->validate([
            'catatan_admin' => 'nullable|string|max:255',
        ]);

        if ($koreksiAbsen->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        // Update atau buat presensi
        $shift = $koreksiAbsen->karyawan->shift;

        $jamMasuk   = Carbon::createFromTimeString($koreksiAbsen->jam_masuk);
        $batasWaktu = Carbon::today()->setTimeFromTimeString($shift->jam_masuk)
            ->addMinutes($shift->toleransi_terlambat);
        $statusAbsen = $jamMasuk->gt($batasWaktu) ? 'terlambat' : 'tepat_waktu';

        $statusPulang = null;
        if ($koreksiAbsen->jam_pulang) {
            $jamPulang      = Carbon::createFromTimeString($koreksiAbsen->jam_pulang);
            $jamPulangShift = Carbon::today()->setTimeFromTimeString($shift->jam_pulang);
            $statusPulang   = $jamPulang->lt($jamPulangShift) ? 'pulang_cepat' : 'tepat_waktu';
        }

        Presensi::updateOrCreate(
            [
                'karyawan_id' => $koreksiAbsen->karyawan_id,
                'tanggal'     => $koreksiAbsen->tanggal,
            ],
            [
                'shift_id'     => $koreksiAbsen->karyawan->shift_id,
                'jam_masuk'    => $koreksiAbsen->jam_masuk,
                'jam_pulang'   => $koreksiAbsen->jam_pulang,
                'status_absen' => $statusAbsen,
                'status_pulang' => $statusPulang,
            ]
        );

        $koreksiAbsen->update([
            'status'        => 'approved',
            'approved_by'   => Auth::id(),
            'approved_at'   => now(),
            'catatan_admin' => $request->catatan_admin,
        ]);

        return back()->with('success', 'Koreksi absen berhasil di-approve dan presensi diperbarui.');
    }

    public function reject(Request $request, KoreksiAbsen $koreksiAbsen)
    {
        $request->validate([
            'catatan_admin' => 'nullable|string|max:255',
        ]);

        if ($koreksiAbsen->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        $koreksiAbsen->update([
            'status'        => 'rejected',
            'approved_by'   => Auth::id(),
            'approved_at'   => now(),
            'catatan_admin' => $request->catatan_admin,
        ]);

        return back()->with('success', 'Koreksi absen berhasil di-reject.');
    }
}