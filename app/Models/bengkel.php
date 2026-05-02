<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Layanan;


class Bengkel extends Model
{
    protected $fillable = ['nama', 'alamat', 'latitude', 'longitude', 'telepon', 'foto', 'deskripsi', 'aktif', 'admin_id'];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function reservasis(): HasMany
    {
        return $this->hasMany(Reservasi::class);
    }

    public function mekaniks(): HasMany
    {
        return $this->hasMany(Mekanik::class);
    }

    public function review(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getRatingRataAttribute(): float
    {
        return round($this->review()->avg('rating') ?? 0, 1);
    }

    public function layanan()
    {
        return $this->belongsToMany(Layanan::class, 'bengkel_layanan');
    }
}