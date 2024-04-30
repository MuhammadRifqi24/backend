<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cafe()
    {
        return $this->belongsTo(Cafe::class);
    }

    public function stan()
    {
        return $this->belongsTo(Stan::class);
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }
}
