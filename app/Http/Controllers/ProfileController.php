<?php

namespace App\Http\Controllers;

use App\Traits\HasImageUpload; // Import Trait
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    use HasImageUpload; // Aktifkan Trait

    /**
     * Tampilkan Halaman Profil
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Update Data Diri (Nama, Email, Foto WebP)
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $messages = [
            'photo.image' => 'File harus berupa gambar.',
            'photo.max'   => 'Ukuran foto maksimal 2MB.',
            'email.unique'=> 'Email ini sudah digunakan orang lain.',
        ];

        // 1. Validasi
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'photo' => [
                'nullable',
                'file',           // Pastikan ini file, bukan string
                'image',          // Pastikan kontennya gambar (pixel), bukan teks script
                'mimes:jpeg,png,jpg', // Ekstensi yang diizinkan
                'max:2028',       // Maksimal 2MB (Mencegah serangan DoS storage penuh)
            ],
        ], $messages);

        // 2. Update Data Dasar
        $user->name  = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');

        // 3. Cek & Proses Upload Foto (Convert WebP)
        if ($request->hasFile('photo')) {
            // Ambil nama file lama (jika ada) untuk dihapus
            // basename() mengambil "foto.jpg" dari "profiles/foto.jpg"
            $oldFilename = $user->photo ? basename($user->photo) : null;

            // Panggil Fungsi Sakti dari Trait
            // Return value: "timestamp_uniqid.webp"
            $filename = $this->uploadCompressed(
                $request->file('photo'),
                'profiles',      // Nama folder di storage/app/public/
                $oldFilename     // File lama untuk dihapus
            );

            // Simpan path lengkap ke database
            $user->photo = 'profiles/' . $filename;
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update Password
     */
    public function updatePassword(Request $request)
    {
        $messages = [
            'current_password.required' => 'Masukkan password lama Anda.',
            'current_password.current_password' => 'Password lama yang Anda masukkan salah.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min'      => 'Password baru minimal 6 karakter.',
            'password.confirmed'=> 'Konfirmasi password baru tidak cocok.',
        ];

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'min:6', 'confirmed'],
        ], $messages);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);

        return back()->with('success', 'Password berhasil diganti! Harap diingat baik-baik.');
    }
}
