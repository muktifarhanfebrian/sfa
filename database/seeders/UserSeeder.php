<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin (Bisa akses semua menu)
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'admin@bintang.com',
            'role' => 'admin',
            'phone' => '081200001111',
            'password' => Hash::make('password'), // Passwordnya: password
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Akun Manager (Bisa lihat laporan)
        DB::table('users')->insert([
            'name' => 'Pak Budi Manager',
            'email' => 'manager@bintang.com',
            'role' => 'manager',
            'phone' => '081200002222',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Akun Sales (Hanya bisa input order & kunjungan)
        DB::table('users')->insert([
            'name' => 'Andi Sales',
            'email' => 'sales@bintang.com',
            'role' => 'sales',
            'phone' => '081200003333',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
            'daily_visit_target' => 5, // Target 5 toko per hari
        ]);
    }
}
