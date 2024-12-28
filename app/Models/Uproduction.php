<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uproduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'uhall_id',
        'production_days',
        'finished_at',
        'created_at',
    ];

    public function uhall()
    {
        return $this->belongsTo(Uhall::class);
    }
}
