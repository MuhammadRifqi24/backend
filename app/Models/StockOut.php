<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    use HasFactory;

    protected $table = 'stock_outs';

    protected $fillable = [
        'stock_id',
        'qty',
        'description',
        'date',
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}
