<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mincrement extends Model
{
    use HasFactory;

    protected $fillable = [
        'mproduction_id',
        'product_id',
        'quantity',
        'weight',
        'price',
        'status',
        'type',
    ];

    public function mproduction()
    {
        return $this->belongsTo(Mproduction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
