<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Layanan;


class Bengkel extends Model
{
    protected $fillable = ['nama', 'alamat', 'latitude', 'longitude', 'kota', 'foto', 'deskripsi', 'status', 'admin_id', 'telepon'];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function adminCabang()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function reservasis(): HasMany
    {
        return $this->hasMany(Reservasi::class);
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

    // satu bengkel bisa punya banyak sparepart, dan satu sparepart bisa ada di banyak bengkel
    public function spareparts()
    {
        return $this->belongsToMany(Sparepart::class, 'bengkel_spareparts')
                    ->withPivot('stok');
    }    

    public function reviews() { return $this->hasMany(Review::class); }    
}