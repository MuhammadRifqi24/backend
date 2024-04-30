<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cafe extends Model
{
    use HasFactory;

    protected $table = 'cafes';

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'logo',
        'address',
        'lat',
        'long',
        'uuid'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cafe_managements(): HasMany
    {
        return $this->hasMany(CafeManagement::class);
    }
}
