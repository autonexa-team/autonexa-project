<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'bengkel_id',
        'type',
        'title',
        'message',
        'is_read',
    ];

    public function bengkel()
    {
        return $this->belongsTo(Bengkel::class);
    }

    Notification::create([
        'bengkel_id' => $reservasi->bengkel_id,
        'type'       => 'reservasi',
        'title'      => 'Reservasi Baru',
        'message'    => auth()->user()->name .
                        ' membuat reservasi baru untuk tanggal ' .
                        $reservasi->tanggal,
    ]);
}