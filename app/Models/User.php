<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'foto_profill'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // RELASI
    public function reservasis()
    {
        return $this->hasMany(Reservasi::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function mekanik()
    {
        return $this->hasOne(Mekanik::class);
    }

    // ROLE CHECK
    public function isAdminPusat() { return $this->role === 'admin_pusat'; }
    public function isAdminCabang() { return $this->role === 'admin_cabang'; }
    public function isMekanik() { return $this->role === 'mekanik'; }
    public function isPelanggan() { return $this->role === 'pelanggan'; }
}