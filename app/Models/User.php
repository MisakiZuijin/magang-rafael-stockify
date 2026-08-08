<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;  // ← GANTI INI
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable  // ← extends Authenticatable, bukan Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Hapus method remember token manual ini (tidak perlu kalau extends Authenticatable):
    // public function getRememberTokenName() { return null; }
    // public function setRememberToken($value) { }

    // Scope
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    // Helper cek role
    public function isAdmin(): bool
    {
        return $this->role === 'Admin';
    }

    public function isManager(): bool
    {
        return $this->role === 'Manager Gudang';
    }

    public function isStaff(): bool
    {
        return $this->role === 'Staff Gudang';
    }

    public function stockTransactions(): HasMany
    {
        return $this->hasMany(StockTransaction::class, 'user_id');
    }
}
