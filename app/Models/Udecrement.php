<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Udecrement extends Model
{
    use HasFactory;

    protected $fillable = [
        'uproduction_id',
        'product_id',
        'quantity',
        'weight',
        'price',
        'status',
    ];

    public function uproduction()
    {
        return $this->belongsTo(Uproduction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
