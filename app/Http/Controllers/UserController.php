<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // 1. Tampilkan Daftar User
    public function index()
    {
        // Ambil semua user, urutkan dari yang terbaru
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    // 2. Form Tambah User Baru
    public function create()
    {
        return view('users.create');
    }

    // 3. Simpan User Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,manager,sales',
            'daily_visit_target' => 'nullable|integer|min:0', // Target visit opsional
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            // Jika kosong, default 0
            'daily_visit_target' => $request->daily_visit_target ?? 0,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    // 4. Form Edit User
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // 5. Update User
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)], // Email boleh sama kalau punya sendiri
            'role' => 'required|in:admin,manager,sales',
            'daily_visit_target' => 'nullable|integer|min:0',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'daily_visit_target' => $request->daily_visit_target ?? 0,
        ];

        // Cek apakah password diisi? Kalau kosong, jangan diupdate
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data user diperbarui!');
    }

    // 6. Hapus User
    public function destroy(User $user)
    {
        // Cegah admin menghapus dirinya sendiri saat login
        if (Auth::id() == $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
