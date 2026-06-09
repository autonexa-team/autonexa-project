<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewFoto extends Model
{
    protected $fillable = ['review_id', 'foto'];

    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
