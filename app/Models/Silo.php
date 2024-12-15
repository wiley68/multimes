<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Silo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function mhalls()
    {
        return $this->hasMany(Mhall::class);
    }

    public function uhalls()
    {
        return $this->hasMany(Uhall::class);
    }
}
