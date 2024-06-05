<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RawMaterialCategory extends Model
{
    use HasFactory;

    protected $table = 'raw_material_categories';

    protected $fillable = [
        'cafe_id',
        'name',
        'description',
        'status',
        'uuid'
    ];

    public function scopeIsActive($query, $status = 1)
    {
        return $query->where('status', $status);
    }

    public function cafe(): BelongsTo
    {
        return $this->belongsTo(Cafe::class);
    }
}
