<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Layanan;

/**
 * @method static Builder where(string $column, mixed $operator = null, mixed $value = null)
 * @method static Builder whereNull(string $column)
 * @method static Builder orWhere(string $column, mixed $operator = null, mixed $value = null)
 * @method static Builder whereNotNull(string $column)
 * @method static \Illuminate\Database\Eloquent\Collection get()
 * @method static int count()
 * @method static bool exists()
 * @method static int update(array $values)
 */
class Bengkel extends Model
{
    protected $fillable = ['nama', 'alamat', 'latitude', 'longitude', 'kota', 'foto', 'deskripsi', 'status', 'admin_id', 'telepon', 'jam_buka', 'jam_tutup', 'hari_operasional', 'kuota_slot'];

    protected $casts = [
        'hari_operasional' => 'json',
        'kuota_slot' => 'json',
    ];

    public function adminCabang(): BelongsTo
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

    public function layanan(): BelongsToMany
    {
        return $this->belongsToMany(Layanan::class, 'bengkel_layanan');
    }

    // satu bengkel bisa punya banyak sparepart, dan satu sparepart bisa ada di banyak bengkel
    public function spareparts(): BelongsToMany
    {
        return $this->belongsToMany(Sparepart::class, 'bengkel_spareparts')
                    ->withPivot('stok')
                    ->withTimestamps();
    }   

    public function reviews() { return $this->hasMany(Review::class); }    

    public function operasional()
    {
        return $this->hasMany(BengkelOperasional::class);
    }

    public function slotReservasi()
    {
        return $this->hasMany(SlotReservasi::class);
    }    

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}