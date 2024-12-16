<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uhall extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'factory_id',
        'silo_id',
    ];

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }

    public function silo()
    {
        return $this->belongsTo(Silo::class);
    }

    public function uproductions()
    {
        return $this->hasMany(Uproduction::class);
    }
}
