<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlotReservasi extends Model
{
    protected $fillable = [
        'bengkel_id',
        'jam_mulai',
        'jam_selesai',
        'kuota'
    ];

    public function bengkel()
    {
        return $this->belongsTo(Bengkel::class);
    }
}