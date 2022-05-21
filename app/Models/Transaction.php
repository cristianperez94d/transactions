<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin_id',
        'destiny_id',
        'value',
        'description',
        'user_id',
    ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timezone('America/Bogota')->format('Y-m-d H:i:s');
    }
}
