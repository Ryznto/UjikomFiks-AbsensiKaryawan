<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $profil = $user->adminProfile;

        return view('admin.profil.index', compact('user', 'profil'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_admin' => 'required|string|max:255',
            'email'      => 'nullable|email|max:255',
            'no_hp'      => 'nullable|string|max:20',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $profil = Auth::user()->adminProfile;

        $data = [
            'nama_admin' => $request->nama_admin,
            'email'      => $request->email,
            'no_hp'      => $request->no_hp,
        ];

        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($profil->foto) {
                Storage::disk('public')->delete($profil->foto);
            }
            $data['foto'] = $request->file('foto')->store('profil', 'public');
        }

        $profil->update($data);

        return redirect()->route('admin.profil.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
{
    $request->validate([
        'password_lama'     => 'required|string',
        'password_baru'     => 'required|string|min:8|confirmed',
    ]);

    /** @var \App\Models\User $user */
    $user = Auth::user();

    if (!Hash::check($request->password_lama, $user->password)) {
        return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.'])->withInput();
    }

    $user->update([
        'password' => Hash::make($request->password_baru),
    ]);

    return redirect()->route('admin.profil.index')
        ->with('success', 'Password berhasil diperbarui.');
}
}