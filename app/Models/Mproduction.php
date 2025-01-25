<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mproduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'mhall_id',
        'production_days',
        'finished_at',
        'stock',
        'price',
        'product_id',
        'created_at',
    ];

    public function mhall()
    {
        return $this->belongsTo(Mhall::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
