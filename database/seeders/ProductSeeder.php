<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Keramik Roman 60x60 Carrara',
                'category' => 'Lantai',
                'price' => 150000.00,
                'stock' => 100,
                'description' => 'Keramik motif marmer putih elegan.',
            ],
            [
                'name' => 'Granit Garuda 60x60 Cream',
                'category' => 'Lantai',
                'price' => 185000.00,
                'stock' => 50,
                'description' => 'Granit polos warna cream, tahan gores.',
            ],
            [
                'name' => 'Wallpaper Vinyl Motif Kayu',
                'category' => 'Dinding',
                'price' => 120000.00,
                'stock' => 25,
                'description' => 'Wallpaper tekstur kayu, ukuran 1 roll 10m.',
            ],
            [
                'name' => 'Gorden Blackout Polos',
                'category' => 'Interior',
                'price' => 85000.00,
                'stock' => 200,
                'description' => 'Kain gorden tebal anti tembus cahaya matahari.',
            ],
            [
                'name' => 'Keramik KIA 40x40 Kamar Mandi',
                'category' => 'Lantai',
                'price' => 65000.00,
                'stock' => 300,
                'description' => 'Tekstur kasar anti licin.',
            ]
        ];

        // Masukkan data ke database
        foreach ($products as $product) {
            $product['created_at'] = now();
            $product['updated_at'] = now();
            DB::table('products')->insert($product);
        }
    }
}
