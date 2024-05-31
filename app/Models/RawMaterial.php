<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RawMaterial extends Model
{
    use HasFactory;

    protected $table = 'raw_materials';

    protected $fillable = [
        'cafe_id',
        'stan_id',
        'raw_material_category_id',
        'name',
        'image',
        'description',
        'harga_beli',
        'harga_jual',
        'is_stock',
        'status',
        'uuid'
    ];

    public function raw_material_category(): BelongsTo
    {
        return $this->belongsTo(RawMaterialCategory::class);
    }

    public function cafe(): BelongsTo
    {
        return $this->belongsTo(Cafe::class);
    }

    public function stan(): BelongsTo
    {
        return $this->belongsTo(Stan::class);
    }

    public function raw_material_stock(): HasOne
    {
        return $this->hasOne(RawMaterialStock::class);
    }
}
