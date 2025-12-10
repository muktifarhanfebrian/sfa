<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    // 1. Tampilkan Halaman Profil
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

    // 2. Update Data Diri (Nama, Email, Foto)
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048', // Max 2MB
        ]);

        // Update Data Dasar
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        // Cek Upload Foto Profil
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada (biar server gak penuh sampah)
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            // Simpan foto baru
            $path = $request->file('photo')->store('profiles', 'public');
            $user->photo = $path; // Pastikan kolom 'photo' ada di tabel users (Nanti kita cek)
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    // 3. Update Password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password', // Fitur validasi bawaan Laravel
            'password' => 'required|min:6|confirmed', // field konfirmasi harus bernama 'password_confirmation'
        ]);

        $user = Auth::user();

        // Update Password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diganti! Jangan lupa ya.');
    }
}
