<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'document',
        'supplier',
        'status',
    ];

    public function subdeliveries()
    {
        return $this->hasMany(Subdelivery::class);
    }
}
