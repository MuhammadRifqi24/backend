<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RawMaterialStockIn extends Model
{
    use HasFactory;

    protected $table = 'raw_material_stock_ins';

    protected $fillable = [
        'raw_material_stock_id',
        'qty',
        'description',
        'date',
    ];

    public function raw_material_stock(): BelongsTo
    {
        return $this->belongsTo(RawMaterialStock::class);
    }
}
