<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;
use App\Models\Bengkel;
use App\Models\Reservasi;
use App\Models\Review;

/**
 * @method static Builder where(string $column, mixed $operator = null, mixed $value = null)
 * @method static Builder whereHas(string $relation, callable $callback = null)
 * @method static Builder whereDoesntHave(string $relation, callable $callback = null)
 * @method static Builder with(string|array $relations)
 * @method static Builder latest(string $column = 'created_at')
 * @method static \Illuminate\Pagination\LengthAwarePaginator paginate(int $perPage = 15)
 * @method int delete()
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'role',
        'phone',
        'foto_profil'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // RELASI
    public function reservasi(): HasMany
    {
        return $this->hasMany(Reservasi::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function bengkel(): HasOne
    {
        return $this->hasOne(Bengkel::class, 'admin_id');
    }

    public function getStatusAttribute()
    {
        return $this->bengkel?->status ?? 'belum';
    }    

    // ROLE CHECK
    public function isAdminPusat() { return $this->role === 'admin_pusat'; }
    public function isAdminCabang() { return $this->role === 'admin_cabang'; }    
    public function isPelanggan() { return $this->role === 'pelanggan'; }

        public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}