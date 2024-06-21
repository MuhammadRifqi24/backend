<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RawMaterialStock extends Model
{
    use HasFactory;

    protected $table = 'raw_material_stocks';

    protected $fillable = [
        'raw_material_id',
        'qty',
        'date',
    ];

    public function raw_material(): BelongsTo
    {
        return $this->belongsTo(RawMaterial::class);
    }

    public function stock_ins(): HasMany
    {
        return $this->hasMany(RawMaterialStockIn::class);
    }

    public function stock_outs(): HasMany
    {
        return $this->hasMany(RawMaterialStockOut::class);
    }
}
