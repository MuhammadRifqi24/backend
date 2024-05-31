<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterialCategory extends Model
{
    use HasFactory;

    protected $table = 'raw_material_categories';

    protected $fillable = [
        'name',
        'description',
        'status',
        'uuid'
    ];

    public function scopeIsActive($query, $status = 1)
    {
        return $query->where('status', $status);
    }
}
