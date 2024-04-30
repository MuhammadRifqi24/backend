<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CafeManagement extends Model
{
    use HasFactory;

    protected $table = 'cafe_management';

    protected $fillable = [
        'cafe_id',
        'stan_id',
        'user_id',
        'userlevel_id',
        'status',
    ];

    public function cafe(): BelongsTo
    {
        return $this->belongsTo(Cafe::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
