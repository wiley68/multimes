<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mhall extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'factory_id',
        'silo_id',
        'type',
    ];

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }

    public function silo()
    {
        return $this->belongsTo(Silo::class);
    }

    public function mproductions()
    {
        return $this->hasMany(Mproduction::class);
    }
}
