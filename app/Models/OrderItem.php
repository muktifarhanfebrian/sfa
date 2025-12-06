<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $guarded = ['id'];

    // Relasi: Item milik Order siapa?
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi: Item ini produk yang mana?
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
