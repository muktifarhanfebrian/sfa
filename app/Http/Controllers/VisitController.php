<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class VisitController extends Controller
{
    // 1. Tampilkan Halaman Form Check-in
    public function create()
    {
        // Mulai Query Customer
        $query = Customer::query();

        // JIKA SALES: Filter hanya toko miliknya
        if (Auth::user()->role === 'sales') {
            $query->where('user_id', Auth::id());
        }

        // Ambil data (Urutkan A-Z biar rapi)
        $customers = $query->orderBy('name')->get();

        return view('visits.create', compact('customers'));
    }

    // 2. Simpan Data Check-in (Bisa Toko Lama atau Baru)
    public function store(Request $request)
    {
        // A. Validasi Umum (GPS & Foto Wajib)
        $rules = [
            'latitude' => 'required',
            'longitude' => 'required',
            'photo' => 'required|image|max:5120',
            'type' => 'required|in:existing,new', // Penanda tipe input
        ];

        // B. Validasi Kondisional
        if ($request->type == 'new') {
            // Kalau Toko Baru: Wajib isi Nama, HP, Alamat
            $rules['new_name'] = 'required|string|max:255';
            $rules['new_phone'] = 'required|string|max:20';
            $rules['new_address'] = 'required|string';
        } else {
            // Kalau Toko Lama: Wajib pilih Customer ID
            $rules['customer_id'] = 'required|exists:customers,id';
        }

        $request->validate($rules, [
            'latitude.required' => 'Lokasi GPS wajib diambil!',
            'customer_id.required' => 'Silakan pilih toko dari daftar.',
            'new_name.required' => 'Nama toko baru wajib diisi.',
        ]);

        // --- LOGIKA UTAMA ---

        // 1. Tentukan Customer ID
        if ($request->type == 'new') {
            // Buat Customer Baru Dulu
            $newCustomer = Customer::create([
                'user_id' => Auth::id(), // <--- TAMBAHKAN INI JUGA DI SINI
                'name' => $request->new_name,
                'phone' => $request->new_phone,
                'address' => $request->new_address,
                'contact_person' => $request->new_contact ?? null, // Opsional
                'top_days' => 0, // Default Cash
                'credit_limit' => 0,
            ]);
            $customerId = $newCustomer->id;
            $msg = 'Toko baru didaftarkan & Check-in berhasil!';
        } else {
            // Pakai Customer Lama
            $customerId = $request->customer_id;
            $msg = 'Check-in berhasil!';
        }

        // 2. Upload Foto
        $photoPath = $request->file('photo')->store('visits', 'public');

        // 3. Simpan Visit
        Visit::create([
            'user_id' => Auth::id(),
            'customer_id' => $customerId,
            'visit_date' => now(),
            'status' => 'completed',
            'completed_at' => now(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'photo_path' => $photoPath,
            'notes' => $request->notes,
        ]);

        return redirect()->route('dashboard')->with('success', $msg);
    }

    // 3. Lihat Riwayat Kunjungan (Admin & Sales)
    public function index(Request $request)
    {
        // 1. Ambil List Sales (Untuk Dropdown Filter)
        $salesList = User::where('role', 'sales')->orderBy('name')->get();

        // 2. Mulai Query Visit
        $query = Visit::with(['user', 'customer']);

        // 3. Cek Role User Login
        if (Auth::user()->role === 'sales') {
            // SALES: Hanya lihat data sendiri (Filter otomatis)
            $query->where('user_id', Auth::id());
        } else {
            // ADMIN: Cek apakah ada Filter dari Dropdown?
            if ($request->has('sales_id') && $request->sales_id != '') {
                $query->where('user_id', $request->sales_id);
            }
        }

        // 4. Ambil Data (Pagination)
        $visits = $query->latest()->paginate(10);

        // 5. Kirim ke View (Jangan lupa kirim $salesList)
        return view('visits.index', compact('visits', 'salesList'));
    }
    // --- FITUR PERENCANAAN (PLANNING) ---

    // 4. Form Buat Rencana
    public function createPlan()
    {
        // Logika sama persis: Filter milik sendiri jika Sales
        $query = Customer::query();

        if (Auth::user()->role === 'sales') {
            $query->where('user_id', Auth::id());
        }

        $customers = $query->orderBy('name')->get();

        return view('visits.plan', compact('customers'));
    }

    // 5. Simpan Rencana (Status: Planned)
    public function storePlan(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'visit_date' => 'required|date|after_or_equal:today', // Gak boleh tanggal kemarin
            'notes' => 'nullable|string',
        ]);

        Visit::create([
            'user_id' => Auth::id(),
            'customer_id' => $request->customer_id,
            'visit_date' => $request->visit_date,
            'status' => 'planned', // Status Awal
            'notes' => $request->notes,
        ]);

        return redirect()->route('dashboard')->with('success', 'Rencana kunjungan berhasil dibuat!');
    }

    // 6. Form Eksekusi Check-in (Dari Rencana)
    public function perform(Visit $visit)
    {
        // Pastikan kunjungan ini milik sales yang login & statusnya masih planned
        if ($visit->user_id != Auth::id() || $visit->status != 'planned') {
            return redirect()->route('dashboard')->with('error', 'Kunjungan tidak valid.');
        }

        return view('visits.perform', compact('visit'));
    }

    // 7. Proses Update Check-in (Simpan GPS & Foto)
    public function update(Request $request, Visit $visit)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'photo' => 'required|image|max:5120',
        ], [
            'latitude.required' => 'Lokasi GPS wajib diambil!',
        ]);

        // Upload Foto
        $photoPath = $request->file('photo')->store('visits', 'public');

        // Update Data Lama
        $visit->update([
            'status' => 'completed',
            'completed_at' => now(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'photo_path' => $photoPath,
            'notes' => $request->notes ?? $visit->notes, // Update catatan jika ada, atau pakai lama
        ]);

        return redirect()->route('dashboard')->with('success', 'Check-in Berhasil! Rencana tuntas.');
    }
}
