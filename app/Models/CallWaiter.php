<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CallWaiter extends Model
{
    use HasFactory;

    protected $table = 'call_waiter';

    protected $fillable = [
        'user_id',
        'table_info_id',
        'cafe_id',
        'note',
        'status',
        'uuid'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function table_info(): BelongsTo
    {
        return $this->belongsTo(TableInfo::class);
    }

    public function cafe(): BelongsTo
    {
        return $this->belongsTo(Cafe::class);
    }
}
