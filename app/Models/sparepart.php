<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    protected $table = 'sparepart';
    protected $fillable = ['nama', 'harga', 'deskripsi'];    

    // satu sparepart bisa ada di banyak bengkel, dan satu bengkel bisa punya banyak sparepart
    public function bengkels()
    {
        return $this->belongsToMany(Bengkel::class, 'bengkel_spareparts')
                    ->withPivot('stok');
    }    
}