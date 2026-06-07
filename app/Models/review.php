<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'bengkel_id', 'reservasi_id', 'rating', 'komentar', 'foto'];

    public function user() { return $this->belongsTo(User::class); }
    public function bengkel() { return $this->belongsTo(Bengkel::class); }
    public function reservasi() { return $this->belongsTo(Reservasi::class); }

    public function fotos()
    {
        return $this->hasMany(ReviewFoto::class);
    }
}