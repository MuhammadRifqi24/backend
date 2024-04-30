<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLevel extends Model
{
    use HasFactory;

    protected $table = 'user_levels';

    protected $fillable = [
        'user_id',
        'level',
        'role',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
