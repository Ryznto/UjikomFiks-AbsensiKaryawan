<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// php artisan serve --host=0.0.0.0 --port=8000 untuk jalanin supaya bisa di akses di hp
class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nip'      => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('nip', 'password');

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'nip' => 'NIP atau password salah.',
            ])->withInput();
        }

        $request->session()->regenerate();

        $role = Auth::user()->role;

        if ($role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('karyawan.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}