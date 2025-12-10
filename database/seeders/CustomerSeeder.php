<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil ID Sales agar data toko punya pemilik
        // Pastikan UserSeeder sudah dijalankan duluan ya!
        $salesA = User::where('email', 'andi@bintang.com')->first();
        $salesB = User::where('email', 'budi@bintang.com')->first();

        // Fallback jika sales belum ada (pakai user pertama)
        $idA = $salesA ? $salesA->id : 1;
        $idB = $salesB ? $salesB->id : 1;

        $customers = [];

        // --- GROUP A: MILIK SALES ANDI (AREA KOTA) ---
        // Toko-toko besar dan proyek di pusat kota

        $namesA = [
            'TB. Anugerah Abadi', 'Depo Bangunan Sejahtera', 'CV. Karya Utama',
            'Toko Besi & Keramik Makmur', 'Proyek Hotel Grand Aceh', 'Mitra 10 (Cabang Kota)',
            'TB. Sinar Pagi', 'Gudang Keramik Pak Haji', 'CV. Konstruksi Banda', 'Rumah Mewah Bu Dokter'
        ];

        foreach ($namesA as $index => $name) {
            $customers[] = [
                'user_id' => $idA, // Milik Andi
                'name' => $name,
                'contact_person' => 'Bpk/Ibu Owner ' . ($index + 1),
                'phone' => '0812' . rand(10000000, 99999999),
                'address' => 'Jl. Protokol Kota No. ' . rand(1, 100) . ', Banda Aceh',
                'top_days' => rand(0, 1) ? 30 : 0, // Random: Tempo 30 hari atau Cash (0)
                'credit_limit' => rand(1, 5) * 10000000, // Limit 10jt - 50jt
                'created_at' => now(), 'updated_at' => now(),
            ];
        }

        // --- GROUP B: MILIK SALES BUDI (AREA BARAT) ---
        // Toko-toko di pinggiran kota atau luar daerah

        $namesB = [
            'TB. Meulaboh Jaya', 'UD. Tani Makmur', 'Toko Bangunan Berkah',
            'CV. Aceh Barat Sentosa', 'Proyek Jembatan Woyla', 'Toko Cat Warna Warni',
            'TB. Harapan Baru', 'Kios Keramik Simpang 4', 'UD. Maju Bersama', 'Kontraktor Jalan Tol'
        ];

        foreach ($namesB as $index => $name) {
            $customers[] = [
                'user_id' => $idB, // Milik Budi
                'name' => $name,
                'contact_person' => 'Ko ' . ['Michael', 'David', 'Sugianto', 'Hendra', 'Rudi'][rand(0,4)],
                'phone' => '0813' . rand(10000000, 99999999),
                'address' => 'Jl. Lintas Barat Sumatera KM ' . rand(50, 200),
                'top_days' => rand(0, 1) ? 45 : 14, // Tempo lebih lama (45 hari) atau pendek
                'credit_limit' => rand(5, 20) * 10000000, // Limit Besar 50jt - 200jt
                'created_at' => now(), 'updated_at' => now(),
            ];
        }

        // Masukkan semua ke database
        DB::table('customers')->insert($customers);
    }
}
