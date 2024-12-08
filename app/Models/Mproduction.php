<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mproduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'mhall_id',
    ];

    public function mhall()
    {
        return $this->belongsTo(Mhall::class);
    }
}
