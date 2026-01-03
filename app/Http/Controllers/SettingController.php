<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Setting;
use App\Models\CustomerCategory;

class SettingController extends Controller
{
    // TAMPILKAN HALAMAN SETTING
    public function index()
    {
        $categories = Category::all();
        $customerCategories = CustomerCategory::all();

        // Ambil settingan jadi array biar gampang dipanggil (key => value)
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('settings.index', compact('categories', 'settings', 'customerCategories'));
    }

    // SIMPAN SETTING UMUM
    public function updateGeneral(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // FIX: Kirim sinyal tab 'general'
        return back()->with('success', 'Pengaturan situs berhasil diperbarui!')->with('active_tab', 'general');
    }

    // TAMBAH KATEGORI PRODUK
    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|unique:categories,name'], [
            'name.unique' => 'Nama kategori produk ini sudah ada.'
        ]);

        Category::create(['name' => $request->name]);

        // FIX: Kirim sinyal tab 'category'
        return back()->with('success', 'Kategori produk baru berhasil ditambahkan!')->with('active_tab', 'category');
    }

    // HAPUS KATEGORI PRODUK
    public function destroyCategory($id)
    {
        try {
            Category::destroy($id);
            return back()->with('success', 'Kategori produk berhasil dihapus.')->with('active_tab', 'category');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal hapus kategori. Pastikan tidak ada produk yang menggunakan kategori ini.')->with('active_tab', 'category');
        }
    }

    // TAMBAH KATEGORI CUSTOMER
    public function storeCustomerCategory(Request $request)
    {
        $request->validate(['name' => 'required|unique:customer_categories,name'], [
            'name.unique' => 'Nama kategori customer ini sudah ada.'
        ]);

        CustomerCategory::create(['name' => $request->name]);

        // FIX: Kirim sinyal tab 'cust-cat'
        return back()->with('success', 'Kategori Customer berhasil ditambahkan!')->with('active_tab', 'cust-cat');
    }

    // HAPUS KATEGORI CUSTOMER
    public function destroyCustomerCategory($id)
    {
        try {
            CustomerCategory::destroy($id);
            return back()->with('success', 'Kategori Customer berhasil dihapus.')->with('active_tab', 'cust-cat');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal hapus kategori. Pastikan tidak ada customer yang menggunakan kategori ini.')->with('active_tab', 'cust-cat');
        }
    }
}
