<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Tampilkan Halaman Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Proses Login
    public function login(Request $request)
    {
        // 1. Validasi Input (Bahasa Indonesia)
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Masukkan alamat email Anda.',
            'email.email'    => 'Format email tidak valid.',
            'password.required' => 'Masukkan kata sandi.',
        ]);

        // 2. Coba Login
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            // Pesan selamat datang (Opsional, bisa dihapus jika ingin hening)
            // return redirect()->intended('dashboard');

            // Atau pakai notifikasi manis:
            $user = Auth::user();
            return redirect()->intended('dashboard')
                ->with('success', "Selamat datang kembali, {$user->name}! 👋");
        }

        // 3. Jika Gagal (Gunakan 'error' agar muncul SweetAlert Merah)
        // Jangan gunakan 'withErrors' karena itu biasanya untuk validasi form (kuning)
        return back()->with('error', 'Email atau Password salah. Silakan cek kembali.');
    }

    // 3. Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
