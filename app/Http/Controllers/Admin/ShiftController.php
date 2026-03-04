<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::withCount('karyawans')->latest()->paginate(10);
        return view('admin.shift.index', compact('shifts'));
    }

    public function create()
    {
        return view('admin.shift.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_shift'          => 'required|string|max:255',
            'jam_masuk'           => 'required',
            'jam_pulang'          => 'required',
            'toleransi_terlambat' => 'required|integer|min:0',
        ]);

        Shift::create([
            'nama_shift'          => $request->nama_shift,
            'jam_masuk'           => $request->jam_masuk,
            'jam_pulang'          => $request->jam_pulang,
            'toleransi_terlambat' => $request->toleransi_terlambat,
        ]);

        return redirect()->route('admin.shift.index')
            ->with('success', 'Shift berhasil ditambahkan.');
    }

    public function edit(Shift $shift)
    {
        return view('admin.shift.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'nama_shift'          => 'required|string|max:255',
            'jam_masuk'           => 'required',
            'jam_pulang'          => 'required',
            'toleransi_terlambat' => 'required|integer|min:0',
        ]);

        $shift->update([
            'nama_shift'          => $request->nama_shift,
            'jam_masuk'           => $request->jam_masuk,
            'jam_pulang'          => $request->jam_pulang,
            'toleransi_terlambat' => $request->toleransi_terlambat,
        ]);

        return redirect()->route('admin.shift.index')
            ->with('success', 'Shift berhasil diperbarui.');
    }

    public function destroy(Shift $shift)
    {
        if ($shift->karyawans()->count() > 0) {
            return redirect()->route('admin.shift.index')
                ->with('error', 'Shift tidak bisa dihapus karena masih memiliki karyawan.');
        }

        $shift->delete();

        return redirect()->route('admin.shift.index')
            ->with('success', 'Shift berhasil dihapus.');
    }
}