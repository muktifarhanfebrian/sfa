<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'daily_visit_target',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
     * Relasi: Satu Sales punya BANYAK Order
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relasi: Satu Sales punya BANYAK Visit (Kunjungan)
     */
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * Relasi: Satu Sales punya BANYAK Customer (Toko Miliknya)
     */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
