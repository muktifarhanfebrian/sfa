<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating; // Pastikan model Rating sudah ada

class RatingController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input (opsional tapi disarankan)
        $request->validate([
            'bintang' => 'required|integer',
            'komentar' => 'nullable|string'
        ]);

        // Simpan ke database
        // Asumsi: Model Rating sudah ada
        Rating::create([
            'score' => $request->bintang,
            'review' => $request->komentar,
            'created_at' => now(),
        ]);

        return response()->json(['message' => 'Sukses masuk database!'], 200);
    }
}
