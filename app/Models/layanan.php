<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'layanans';

    protected $fillable = [
        'nama',
        'deskripsi',
        'durasi',
        'harga'
    ];

    public function bengkels()
    {
        return $this->belongsToMany(Bengkel::class, 'bengkel_layanan');
    }
}