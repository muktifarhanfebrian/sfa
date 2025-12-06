<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Nama tabel (Opsional jika nama tabel jamak dari nama model, tapi aman ditulis)
    protected $table = 'products';

    // Kolom yang boleh diisi secara massal (Create/Update)
    protected $fillable = [
        'name',
        'category',
        'price',
        'stock',
        'description',
        'image',
    ];

    // Opsional: Casting tipe data agar 'price' selalu dianggap angka/integer saat diambil
    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];
}
