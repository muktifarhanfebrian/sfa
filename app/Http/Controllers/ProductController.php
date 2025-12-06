<?php

namespace App\Http\Controllers;

use App\Models\Product; // Jangan lupa import Model ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Menampilkan Daftar Produk
    public function index()
    {
        // Ambil data produk, urutkan terbaru, dan batasi 10 per halaman
        $products = Product::latest()->paginate(10);

        // Kirim variabel $products ke view
        return view('products.index', compact('products'));
    }
    // 1. Tampilkan Form Tambah Produk
    public function create()
    {
        return view('products.create');
    }

    // 2. Proses Simpan Data ke Database
    public function store(Request $request)
    {
        // A. Validasi Input
        $request->validate([
            'name' => 'required|min:3',
            'category' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB
        ]);

        // B. Upload Gambar (Jika ada)
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Simpan di folder: storage/app/public/products
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // C. Simpan ke Database menggunakan Model
        Product::create([
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'image' => $imagePath, // Simpan path filenya saja
        ]);

        // D. Redirect kembali ke index
        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }
    // 3. Tampilkan Form Edit (dengan data lama)
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    // 4. Proses Update Data
    public function update(Request $request, Product $product)
    {
        // Validasi (mirip create, tapi image tidak wajib/nullable)
        $request->validate([
            'name' => 'required|min:3',
            'category' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Siapkan data yang mau diupdate
        $data = [
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
        ];

        // Cek apakah user upload gambar baru?
        if ($request->hasFile('image')) {
            // A. Hapus gambar lama jika ada
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // B. Upload gambar baru
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Eksekusi update
        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    // 5. Proses Hapus Data
    public function destroy(Product $product)
    {
        // Hapus gambar fisik di storage dulu (bersih-bersih)
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // Hapus data di database
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
