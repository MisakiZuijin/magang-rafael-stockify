<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

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

    public function stockTransactions(): HasMany
    {
        return $this->hasMany(StockTransaction::class, 'user_id');
    }
}
