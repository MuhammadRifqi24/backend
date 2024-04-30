<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cafe_managements()
    {
        return $this->hasMany(CafeManagement::class);
    }
}
