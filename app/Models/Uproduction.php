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
        'finished_at',
        'stock',
        'price',
        'product_id',
        'group_number',
        'partida_number',
        'created_at',
    ];

    public function uhall()
    {
        return $this->belongsTo(Uhall::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
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
