<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    public function getRememberTokenName()
    {
        return null; // atau return string kosong
    }

    public function setRememberToken($value)
    {
        // kosongkan, tidak simpan apa-apa
    }

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

    // Scope untuk filter berdasarkan role
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    // Helper cek role
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function stockTransactions(): HasMany
    {
        return $this->hasMany(StockTransaction::class, 'user_id');
    }
}
