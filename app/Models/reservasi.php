<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservasi extends Model
{
    protected $fillable = [
        'user_id', 'bengkel_id', 'mekanik_id',
        'tanggal', 'waktu', 'keluhan',
        'status', 'hasil_service', 'total_biaya',
    ];

    protected $casts = ['tanggal' => 'date'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function bengkel(): BelongsTo { return $this->belongsTo(Bengkel::class); }
    public function mekanik(): BelongsTo { return $this->belongsTo(Mekanik::class); }
    public function spareparts(): HasMany { return $this->hasMany(Sparepart::class); }
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