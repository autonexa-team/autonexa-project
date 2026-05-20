<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BengkelOperasional extends Model
{
    protected $fillable = [
        'bengkel_id',
        'hari',
        'is_buka'
    ];

    public function bengkel()
    {
        return $this->belongsTo(Bengkel::class);
    }
}