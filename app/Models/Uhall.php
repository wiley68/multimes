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
    ];

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }
}
