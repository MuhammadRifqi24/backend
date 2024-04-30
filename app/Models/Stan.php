<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
