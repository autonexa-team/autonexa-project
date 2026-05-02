<!-- app/Models/Sparepart.php -->


<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    protected $fillable = ['reservasi_id', 'nama', 'qty', 'harga', 'total'];

    public function reservasi() { return $this->belongsTo(Reservasi::class); }
}