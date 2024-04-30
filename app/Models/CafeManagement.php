<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function cafe()
    {
        return $this->belongsTo(Cafe::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
