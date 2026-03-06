<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index()
    {
        $user     = Auth::user();
        $karyawan = $user->karyawan->load(['divisi', 'jabatan', 'shift']);

        return view('karyawan.profil', compact('user', 'karyawan'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required|string',
            'password_baru' => 'required|string|min:8|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors([
                'password_lama' => 'Password lama tidak sesuai.'
            ])->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password_baru),
        ]);

        return redirect()->route('karyawan.profil')
            ->with('success', 'Password berhasil diperbarui.');
    }
}