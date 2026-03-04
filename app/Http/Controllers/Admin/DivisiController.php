<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    public function index()
    {
        $divisis = Divisi::withCount('jabatans', 'karyawans')->latest()->paginate(10);
        return view('admin.divisi.index', compact('divisis'));
    }

    public function create()
    {
        return view('admin.divisi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_divisi' => 'required|string|max:255|unique:divisi,nama_divisi',
        ]);

        Divisi::create(['nama_divisi' => $request->nama_divisi]);

        return redirect()->route('admin.divisi.index')
            ->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function edit(Divisi $divisi)
    {
        return view('admin.divisi.edit', compact('divisi'));
    }

    public function update(Request $request, Divisi $divisi)
    {
        $request->validate([
            'nama_divisi' => 'required|string|max:255|unique:divisi,nama_divisi,' . $divisi->id,
        ]);

        $divisi->update(['nama_divisi' => $request->nama_divisi]);

        return redirect()->route('admin.divisi.index')
            ->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Divisi $divisi)
    {
        if ($divisi->karyawans()->count() > 0) {
            return redirect()->route('admin.divisi.index')
                ->with('error', 'Divisi tidak bisa dihapus karena masih memiliki karyawan.');
        }

        $divisi->delete();

        return redirect()->route('admin.divisi.index')
            ->with('success', 'Divisi berhasil dihapus.');
    }
}