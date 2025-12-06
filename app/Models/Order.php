<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id']; // Semua boleh diisi kecuali ID

    // Relasi: 1 Order dimiliki 1 Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi: 1 Order dibuat oleh 1 User (Sales)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: 1 Order punya BANYAK Item
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
