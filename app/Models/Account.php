<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const TYPE_PROPIA = 'propia';
    const TYPE_TERCEROS = 'terceros';

    protected $fillable = [
        'account',
        'current_value',
        'type',
        'state',
        'user_id',
    ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}
