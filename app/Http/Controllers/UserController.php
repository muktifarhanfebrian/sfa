<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = $this->getRolesList(); // Saya buat fungsi helper di bawah biar rapi
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $messages = [
            'name.required'     => 'Nama lengkap wajib diisi.',
            'email.required'    => 'Email wajib diisi untuk login.',
            'email.unique'      => 'Email ini sudah dipakai oleh user lain.',
            'password.min'      => 'Password minimal 6 karakter biar aman.',
            'role.required'     => 'Jabatan/Role belum dipilih.',
        ];

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required',
            'daily_visit_target' => 'nullable|integer|min:0',
        ], $messages);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'daily_visit_target' => $request->daily_visit_target ?? 0,
        ]);

        return redirect()->route('users.index')->with('success', 'Anggota tim baru berhasil ditambahkan! 🎉');
    }

    // --- PERBAIKAN DI SINI ---
    public function edit(User $user)
    {
        // Kita panggil daftar role agar variabel $roles tersedia di view
        $roles = $this->getRolesList();

        // Kirim $user DAN $roles ke view
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $messages = [
            'email.unique' => 'Gagal update. Email ini sudah dipakai user lain.',
        ];

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'role' => 'required',
        ], $messages);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'sales_target' => $request->sales_target ?? 0,
            'daily_visit_target' => $request->daily_visit_target ?? 5,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if (Auth::id() == $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    public function updateSalesTarget(Request $request)
    {
        if (!in_array(Auth::user()->role, ['manager_bisnis', 'manager_operasional'])) {
            abort(403, 'Anda tidak memiliki akses.');
        }
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'target'  => 'required|numeric|min:0'
        ]);
        $sales = \App\Models\User::findOrFail($request->user_id);
        $sales->update(['sales_target' => $request->target]);
        return back()->with('success', 'Target omset berhasil diperbarui!');
    }

    // --- HELPER FUNCTION BIAR TIDAK DUPLIKAT KODE ---
    private function getRolesList()
    {
        $roles = [
            'manager_bisnis'      => 'Manager Bisnis',
            'manager_operasional' => 'Manager Operasional',
            'kepala_gudang'       => 'Kepala Gudang',
            'admin_gudang'        => 'Admin Gudang',
            'purchase'            => 'Purchase (Pembelian)',
            'finance'             => 'Finance (Keuangan)',
            'kasir'               => 'Kasir', // Saya tambahkan Kasir karena ada di file lain
            'sales_store'         => 'Sales Toko',
            'sales_field'         => 'Sales Lapangan',
        ];

        // Opsional: Sembunyikan Manager Operasional dari dropdown jika bukan Super Admin
        // unset($roles['manager_operasional']);

        return $roles;
    }
}
