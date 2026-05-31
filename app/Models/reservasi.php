<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservasi extends Model
{
    protected $fillable = [
        'user_id', 'bengkel_id', 'layanan_id', 'mekanik_id',
        'tanggal', 'waktu', 'kendaraan', 'plat', 'keluhan',
        'status', 'hasil_service', 'total_biaya',
    ];

    protected $casts = ['tanggal' => 'date'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function bengkel(): BelongsTo { return $this->belongsTo(Bengkel::class); }
    public function layanan(): BelongsTo { return $this->belongsTo(Layanan::class); }
    
    public function spareparts()
    {
        return $this->belongsToMany(
            Sparepart::class,
            'reservasi_spareparts'
        )->withPivot([
            'qty',
            'harga'
        ])->withTimestamps();
    }
    
    public function review() { return $this->hasOne(Review::class); }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'       => 'Menunggu Konfirmasi',
            'dikonfirmasi'  => 'Dikonfirmasi',
            'diproses'      => 'Sedang Diproses',
            'selesai'       => 'Selesai',
            'dibatalkan'    => 'Dibatalkan',
            default         => '-',
        };
    }
}