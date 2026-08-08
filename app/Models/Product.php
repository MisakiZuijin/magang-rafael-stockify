<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'supplier_id',
        'name',
        'description',
        'sku',
        'purchase_price',
        'selling_price',
        'image',
        'stock',
        'minimum_stock',
    ];

    public function categori(): BelongsTo
    {
        return $this->belongsTo(Categori::class, 'category_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function productAttributs(): HasMany
    {
        return $this->hasMany(ProductAttribut::class, 'product_id');
    }
}
