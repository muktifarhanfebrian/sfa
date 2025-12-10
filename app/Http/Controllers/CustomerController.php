<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CustomerController extends Controller
{
    // 1. Tampilkan Daftar Toko
    public function index(Request $request)
    {
        // 1. Ambil List Sales untuk Dropdown Filter
        $salesList = User::where('role', 'sales')->orderBy('name')->get();

        // 2. Mulai Query Customer
        $query = Customer::with(['user']);

        // 3. Cek Role User Login
        if (Auth::user()->role === 'sales') {
            // SALES: Hanya lihat order milik sendiri
            $query->where('user_id', Auth::id());
        } else {
            // ADMIN: Cek apakah ada Filter dari Dropdown?
            if ($request->has('sales_id') && $request->sales_id != '') {
                $query->where('user_id', $request->sales_id);
            }
        }

        // 4. Ambil Data (Pagination)
        $customers = $query->latest()->paginate(10);

        // 5. Kirim ke View
        return view('customers.index', compact('customers', 'salesList'));
    }

    // 2. Form Tambah Toko (Bisa diakses Sales)
    public function create()
    {
        return view('customers.create');
    }

    // 3. Simpan Toko Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            // Validasi tambahan
            'top_days' => 'nullable|integer|min:0',
            'credit_limit' => 'nullable|numeric|min:0',
        ]);

        Customer::create([
            'user_id' => Auth::id(), // <--- OTOMATIS CATAT PEMILIK
            'name' => $request->name,
            'contact_person' => $request->contact_person,
            'phone' => $request->phone,
            'address' => $request->address,
            // Ambil input user, kalau kosong set 0
            'top_days' => $request->top_days ?? 0,
            'credit_limit' => $request->credit_limit ?? 0,
        ]);

        return redirect()->route('customers.index')->with('success', 'Toko berhasil didaftarkan!');
    }

    // 4. Form Edit (Admin Only)
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    // 5. Update Data
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'address' => 'required|string',
            'top_days' => 'nullable|integer|min:0',
            'credit_limit' => 'nullable|numeric|min:0',
        ]);

        // Update semua field (termasuk top_days & credit_limit)
        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Data toko diperbarui!');
    }

    // 6. Hapus Data
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Toko berhasil dihapus.');
    }
}
