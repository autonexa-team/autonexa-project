<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Bengkel;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'foto_profil'
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

    public function bengkel()
    {
        return $this->hasOne(Bengkel::class, 'admin_id');
    }

    public function getStatusAttribute()
    {
        return $this->bengkel?->status ?? 'belum';
    }    

    // ROLE CHECK
    public function isAdminPusat() { return $this->role === 'admin_pusat'; }
    public function isAdminCabang() { return $this->role === 'admin_cabang'; }    
    public function isPelanggan() { return $this->role === 'pelanggan'; }
}