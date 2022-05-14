<?php
namespace App\MyConfig;

use App\Models\User;

class Globals
{
    const USER_TYPE = [
        User::ADMIN,
        User::REGULAR, 
    ];
    
    const ACCOUNT_TYPE = [
        'propia', 
        'terceros',
    ];
    
    const ACCOUNT_STATE = [
        'active', 
        'inactive',
    ];
}
