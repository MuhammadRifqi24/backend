<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TableInfo extends Model
{
    use HasFactory;

    protected $table = 'table_info';

    protected $fillable = [
        'cafe_id',
        'name',
        'status',
        'number',
        'uuid'
    ];

    public function cafe(): BelongsTo
    {
        return $this->belongsTo(Cafe::class);
    }
}
