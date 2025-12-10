<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'admin@bintang.com',
            'role' => 'admin',
            'phone' => '081100001111',
            'password' => Hash::make('password'),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // 2. Manager (Bisa lihat grafik)
        DB::table('users')->insert([
            'name' => 'Pak Budi Manager',
            'email' => 'manager@bintang.com',
            'role' => 'manager',
            'phone' => '081100002222',
            'password' => Hash::make('password'),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // 3. Sales A (Andi - Rajin)
        DB::table('users')->insert([
            'name' => 'Andi Sales (Area Kota)',
            'email' => 'andi@bintang.com',
            'role' => 'sales',
            'phone' => '081200003333',
            'daily_visit_target' => 5,
            'password' => Hash::make('password'),
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // 4. Sales B (Budi - Area Luar)
        DB::table('users')->insert([
            'name' => 'Budi Sales (Area Barat)',
            'email' => 'budi@bintang.com',
            'role' => 'sales',
            'phone' => '081200004444',
            'daily_visit_target' => 5,
            'password' => Hash::make('password'),
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }
}
