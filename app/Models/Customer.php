<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'address',
        'latitude',  // Opsional, siapa tahu nanti mau pakai Maps
        'longitude',
    ];

    /**
     * Relasi: Satu Customer bisa memiliki BANYAK Order (Riwayat Belanja)
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
