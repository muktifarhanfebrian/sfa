<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // <--- Tambahkan ini
        'name',
        'contact_person',
        'phone',
        'address',
        'latitude',  // Opsional, siapa tahu nanti mau pakai Maps
        'longitude',
        'top_days',
        'credit_limit',
    ];
    protected $casts = [
        'top_days' => 'integer',
        'credit_limit' => 'decimal:2',
    ];
    // Relasi: Customer dimiliki oleh satu Sales (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Satu Customer bisa memiliki BANYAK Order (Riwayat Belanja)
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
