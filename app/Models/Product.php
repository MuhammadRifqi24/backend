<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'cafe_id',
        'stan_id',
        'category_id',
        'name',
        'image',
        'description',
        'harga_beli',
        'harga_jual',
        'is_stock',
        'status',
        'uuid'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cafe(): BelongsTo
    {
        return $this->belongsTo(Cafe::class);
    }

    public function stan(): BelongsTo
    {
        return $this->belongsTo(Stan::class);
    }

    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class);
    }
}
