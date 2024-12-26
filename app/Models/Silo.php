<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Silo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'factory_id',
        'maxqt',
        'stock',
        'product_id',
    ];

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function mhalls()
    {
        return $this->hasMany(Mhall::class);
    }

    public function uhalls()
    {
        return $this->hasMany(Uhall::class);
    }
}
