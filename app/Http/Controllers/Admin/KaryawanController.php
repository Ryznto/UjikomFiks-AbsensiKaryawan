<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\User;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::with(['user', 'divisi', 'jabatan', 'shift'])
            ->latest()
            ->paginate(10);

        return view('admin.karyawan.index', compact('karyawans'));
    }

    public function create()
    {
        $divisis  = Divisi::all();
        $jabatans = Jabatan::with('divisi')->get();
        $shifts   = Shift::all();

        return view('admin.karyawan.create', compact('divisis', 'jabatans', 'shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip'        => 'required|string|unique:users,nip',
            'nama'       => 'required|string|max:255',
            'divisi_id'  => 'required|exists:divisi,id',
            'jabatan_id' => 'required|exists:jabatan,id',
            'shift_id'   => 'required|exists:shift,id',
            'no_hp'      => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($request) {
            // Generate password otomatis
            $password = Str::random(8);

            // Buat user
            $user = User::create([
                'nip'      => $request->nip,
                'password' => Hash::make($password),
                'role'     => 'karyawan',
            ]);

            // Buat karyawan
            Karyawan::create([
                'user_id'    => $user->id,
                'nama'       => $request->nama,
                'divisi_id'  => $request->divisi_id,
                'jabatan_id' => $request->jabatan_id,
                'shift_id'   => $request->shift_id,
                'no_hp'      => $request->no_hp,
                'status_aktif' => true,
            ]);

            // Simpan password plain ke session buat ditampilin ke admin
            session(['generated_password' => $password, 'generated_nip' => $request->nip]);
        });

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Karyawan berhasil ditambahkan.')
            ->with('show_password', true);
    }

    public function show(Karyawan $karyawan)
    {
        $karyawan->load(['user', 'divisi', 'jabatan', 'shift']);
        return view('admin.karyawan.show', compact('karyawan'));
    }

    public function edit(Karyawan $karyawan)
    {
        $divisis  = Divisi::all();
        $jabatans = Jabatan::with('divisi')->get();
        $shifts   = Shift::all();

        return view('admin.karyawan.edit', compact('karyawan', 'divisis', 'jabatans', 'shifts'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'nip'        => 'required|string|unique:users,nip,' . $karyawan->user_id,
            'nama'       => 'required|string|max:255',
            'divisi_id'  => 'required|exists:divisi,id',
            'jabatan_id' => 'required|exists:jabatan,id',
            'shift_id'   => 'required|exists:shift,id',
            'no_hp'      => 'nullable|string|max:20',
            'status_aktif' => 'boolean',
        ]);

        DB::transaction(function () use ($request, $karyawan) {
            $karyawan->user->update([
                'nip' => $request->nip,
            ]);

            $karyawan->update([
                'nama'        => $request->nama,
                'divisi_id'   => $request->divisi_id,
                'jabatan_id'  => $request->jabatan_id,
                'shift_id'    => $request->shift_id,
                'no_hp'       => $request->no_hp,
                'status_aktif' => $request->boolean('status_aktif'),
            ]);
        });

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Karyawan $karyawan)
    {
        DB::transaction(function () use ($karyawan) {
            $user = $karyawan->user;
            $karyawan->delete();
            $user->delete();
        });

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Karyawan berhasil dihapus.');
    }

    public function resetPassword(Karyawan $karyawan)
    {
        $password = Str::random(8);

        $karyawan->user->update([
            'password' => Hash::make($password),
        ]);

        return redirect()->route('admin.karyawan.show', $karyawan)
            ->with('success', 'Password berhasil direset.')
            ->with('generated_password', $password)
            ->with('generated_nip', $karyawan->user->nip);
    }
}