<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VisitController extends Controller
{
    // 1. Tampilkan Halaman Form Check-in
    public function create()
    {
        // Ambil data customer untuk dropdown
        $customers = Customer::orderBy('name')->get();
        return view('visits.create', compact('customers'));
    }

    // 2. Simpan Data Check-in (GPS + Foto)
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'latitude' => 'required',  // Wajib ada koordinat
            'longitude' => 'required', // Wajib ada koordinat
            'photo' => 'required|image|max:5120', // Wajib foto, max 5MB
            'notes' => 'nullable|string',
        ], [
            'latitude.required' => 'Lokasi GPS wajib diaktifkan!',
            'photo.required' => 'Bukti foto wajib diupload!',
        ]);

        // A. Upload Foto
        $photoPath = $request->file('photo')->store('visits', 'public');

        // B. Simpan ke Database
        Visit::create([
            'user_id' => Auth::id(),
            'customer_id' => $request->customer_id,
            'visit_date' => now(), // Tanggal hari ini
            'status' => 'completed', // Langsung dianggap selesai karena check-in di tempat
            'completed_at' => now(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'photo_path' => $photoPath,
            'notes' => $request->notes,
        ]);

        return redirect()->route('dashboard')->with('success', 'Check-in berhasil! Kunjungan tercatat.');
    }

    // 3. Lihat Riwayat Kunjungan (Admin & Sales)
    public function index()
    {
        // Jika Admin: Lihat semua data
        if (Auth::user()->role === 'admin' || Auth::user()->role === 'manager') {
            $visits = Visit::with(['user', 'customer'])->latest()->paginate(10);
        } else {
            // Jika Sales: Hanya lihat data sendiri
            $visits = Visit::with(['user', 'customer'])
                        ->where('user_id', Auth::id())
                        ->latest()
                        ->paginate(10);
        }

        return view('visits.index', compact('visits'));
    }
}
