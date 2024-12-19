<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdelivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_id',
        'product_id',
        'quantity',
        'price',
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
