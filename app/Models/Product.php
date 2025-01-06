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
        'me',
        'type'
    ];

    public function subdeliveries()
    {
        return $this->hasMany(Subdelivery::class);
    }

    public function silos()
    {
        return $this->hasMany(Silo::class);
    }

    public function uproductions()
    {
        return $this->hasMany(Uproduction::class);
    }

    public function udecrements()
    {
        return $this->hasMany(Udecrement::class);
    }

    public function uincrements()
    {
        return $this->hasMany(Uincrement::class);
    }
}
