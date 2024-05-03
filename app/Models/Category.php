<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

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
