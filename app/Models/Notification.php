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

}