<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservasiSparepart extends Model
{
    protected $table = 'reservasi_spareparts';

    protected $fillable = [
        'reservasi_id',
        'nama',
        'qty',
        'harga',
        'keterangan',
    ];

    protected $casts = [
        'qty'   => 'integer',
        'harga' => 'integer',
    ];

    public function reservasi(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Reservasi::class, 'reservasi_id');
    }
}