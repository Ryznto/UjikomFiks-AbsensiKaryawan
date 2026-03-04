<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\Divisi;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatans = Jabatan::with('divisi')->withCount('karyawans')->latest()->paginate(10);
        return view('admin.jabatan.index', compact('jabatans'));
    }

    public function create()
    {
        $divisis = Divisi::all();
        return view('admin.jabatan.create', compact('divisis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
            'divisi_id'    => 'required|exists:divisi,id',
        ]);

        Jabatan::create([
            'nama_jabatan' => $request->nama_jabatan,
            'divisi_id'    => $request->divisi_id,
        ]);

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function edit(Jabatan $jabatan)
    {
        $divisis = Divisi::all();
        return view('admin.jabatan.edit', compact('jabatan', 'divisis'));
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
            'divisi_id'    => 'required|exists:divisi,id',
        ]);

        $jabatan->update([
            'nama_jabatan' => $request->nama_jabatan,
            'divisi_id'    => $request->divisi_id,
        ]);

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan)
    {
        if ($jabatan->karyawans()->count() > 0) {
            return redirect()->route('admin.jabatan.index')
                ->with('error', 'Jabatan tidak bisa dihapus karena masih memiliki karyawan.');
        }

        $jabatan->delete();

        return redirect()->route('admin.jabatan.index')
            ->with('success', 'Jabatan berhasil dihapus.');
    }
}