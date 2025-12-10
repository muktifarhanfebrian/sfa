<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // KATEGORI: KERAMIK LANTAI
            [
                'name' => 'Keramik Roman 40x40 Putih Polos',
                'category' => 'Lantai',
                'price' => 65000,
                'stock' => 500,
                'description' => 'Keramik standar kualitas grade A, cocok untuk perumahan.',
            ],
            [
                'name' => 'Keramik KIA 40x40 Motif Kayu Coklat',
                'category' => 'Lantai',
                'price' => 68000,
                'stock' => 250,
                'description' => 'Tekstur matte anti licin, nuansa alami.',
            ],
            [
                'name' => 'Keramik Mulia 50x50 Grey Stone',
                'category' => 'Lantai',
                'price' => 75000,
                'stock' => 120,
                'description' => 'Motif batu alam abu-abu, cocok untuk teras.',
            ],

            // KATEGORI: GRANIT (MEWAH)
            [
                'name' => 'Granit Indogress 60x60 Cream Polos',
                'category' => 'Granit',
                'price' => 185000,
                'stock' => 200,
                'description' => 'Double loading, kilap sempurna, tahan gores.',
            ],
            [
                'name' => 'Granit Garuda 60x60 Hitam Corak Emas',
                'category' => 'Granit',
                'price' => 210000,
                'stock' => 80,
                'description' => 'Nuansa mewah dan elegan untuk ruang tamu besar.',
            ],
            [
                'name' => 'Granit Wisma Sehati 80x80 Carrara White',
                'category' => 'Granit',
                'price' => 320000,
                'stock' => 50,
                'description' => 'Ukuran besar (Big Slab), motif marmer Italia.',
            ],

            // KATEGORI: KERAMIK DINDING (KM MANDI / DAPUR)
            [
                'name' => 'Keramik Dinding Roman 25x50 Putih Bevel',
                'category' => 'Dinding',
                'price' => 85000,
                'stock' => 150,
                'description' => 'Gaya subway tiles modern untuk backsplash dapur.',
            ],
            [
                'name' => 'Keramik Dinding Platinum 25x40 Motif Bunga',
                'category' => 'Dinding',
                'price' => 62000,
                'stock' => 300,
                'description' => 'Set dengan keramik lantai kamar mandi.',
            ],

            // KATEGORI: INTERIOR (VINYL & WALLPAPER)
            [
                'name' => 'Vinyl Lantai Taco 3mm Wood Series',
                'category' => 'Interior',
                'price' => 145000,
                'stock' => 100, // Harga per meter/box
                'description' => 'Lantai vinyl motif kayu, pemasangan sistem lem.',
            ],
            [
                'name' => 'SPC Flooring Kendo 4mm (Click System)',
                'category' => 'Interior',
                'price' => 250000,
                'stock' => 60,
                'description' => 'Anti air, anti rayap, sistem klik tanpa lem.',
            ],
            [
                'name' => 'Wallpaper Dinding Korea (Roll Besar)',
                'category' => 'Interior',
                'price' => 350000,
                'stock' => 40,
                'description' => 'Ukuran 1x15 meter, motif damask mewah.',
            ],
            [
                'name' => 'Gorden Vertical Blind Onna (Dimout)',
                'category' => 'Interior',
                'price' => 275000,
                'stock' => 5, // STOK SEDIKIT (Biar muncul notif "Stok Menipis")
                'description' => 'Tirai modern untuk kantor, cahaya masuk 30%.',
            ],

            // KATEGORI: SANITARY
            [
                'name' => 'Closet Duduk TOTO CW633',
                'category' => 'Sanitary',
                'price' => 1850000,
                'stock' => 15,
                'description' => 'Closet duduk standar, dual flush hemat air.',
            ],
            [
                'name' => 'Kran Cuci Piring Fleksibel Onda',
                'category' => 'Sanitary',
                'price' => 125000,
                'stock' => 4, // STOK SEDIKIT (Biar muncul notif)
                'description' => 'Kran angsa fleksibel bahan stainless steel.',
            ]
        ];

        foreach ($products as $product) {
            $product['created_at'] = now();
            $product['updated_at'] = now();
            DB::table('products')->insert($product);
        }
    }
}
