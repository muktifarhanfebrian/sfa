<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('customers')->insert([
            // Pelanggan 1
            [
                'name' => 'Toko Bangunan Jaya Abadi',
                'contact_person' => 'Pak Budi',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 10, Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
                'top_days' => 30,          // <--- Masukkan di sini
                'credit_limit' => 10000000, // <--- Masukkan di sini
            ],
            // Pelanggan 2
            [
                'name' => 'Rumah Bu Susi (Renovasi)',
                'contact_person' => 'Ibu Susi',
                'phone' => '081298765432',
                'address' => 'Komplek Permata Hijau Blok A1',
                'created_at' => now(),
                'updated_at' => now(),
                'top_days' => 14,          // Boleh beda-beda tiap orang
                'credit_limit' => 5000000,
            ],
            // Pelanggan 3
            [
                'name' => 'TB. Sinar Terang',
                'contact_person' => 'Ko Michael',
                'phone' => '081999888777',
                'address' => 'Jl. Sudirman No. 55',
                'created_at' => now(),
                'updated_at' => now(),
                'top_days' => 30,
                'credit_limit' => 15000000,
            ],
        ]);
    }
}
