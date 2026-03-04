<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IzinCuti;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IzinCutiController extends Controller
{
    public function index(Request $request)
    {
        $query = IzinCuti::with(['karyawan.user', 'karyawan.divisi', 'approvedBy']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->jenis) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->divisi_id) {
            $query->whereHas('karyawan', fn($q) => $q->where('divisi_id', $request->divisi_id));
        }

        if ($request->tanggal) {
            $query->whereDate('tanggal_mulai', '<=', $request->tanggal)
                  ->whereDate('tanggal_selesai', '>=', $request->tanggal);
        }

        $izinCutis = $query->latest()->paginate(15)->withQueryString();
        $divisis   = Divisi::all();

        $totalPending  = IzinCuti::where('status', 'pending')->count();
        $totalApproved = IzinCuti::where('status', 'approved')->count();
        $totalRejected = IzinCuti::where('status', 'rejected')->count();

        return view('admin.izin-cuti.index', compact(
            'izinCutis',
            'divisis',
            'totalPending',
            'totalApproved',
            'totalRejected'
        ));
    }

    public function approve(IzinCuti $izinCuti)
    {
        if ($izinCuti->status !== 'pending') {
            return redirect()->route('admin.izin-cuti.index')
                ->with('error', 'Pengajuan ini sudah diproses.');
        }

        $izinCuti->update([
            'status'      => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.izin-cuti.index')
            ->with('success', 'Pengajuan berhasil di-approve.');
    }

    public function reject(Request $request, IzinCuti $izinCuti)
    {
        if ($izinCuti->status !== 'pending') {
            return redirect()->route('admin.izin-cuti.index')
                ->with('error', 'Pengajuan ini sudah diproses.');
        }

        $izinCuti->update([
            'status'      => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.izin-cuti.index')
            ->with('success', 'Pengajuan berhasil di-reject.');
    }
}