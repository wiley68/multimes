<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Factory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city_id',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
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
