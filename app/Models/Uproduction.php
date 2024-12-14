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
        'created_at',
    ];

    public function uhall()
    {
        return $this->belongsTo(Uhall::class);
    }
}
