<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nomenklature',
        'description',
        'price',
        'stock',
        'me'
    ];

    public function subdeliveries()
    {
        return $this->hasMany(Subdelivery::class);
    }
}
