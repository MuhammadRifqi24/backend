<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stan extends Model
{
    use HasFactory;

    protected $table = 'stans';

    protected $fillable = [
        'cafe_id',
        'user_id',
        'userlevel_id',
        'name',
        'description',
        'logo',
        'status',
        'uuid',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
