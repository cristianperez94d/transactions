<?php
namespace App\MyConfig;

use App\Models\User;
use App\Models\Account;

class Globals
{
    const USER_TYPE = [
        User::ADMIN,
        User::REGULAR, 
    ];
    
    const ACCOUNT_TYPE = [
        Account::TYPE_PROPIA, 
        Account::TYPE_TERCEROS,
    ];
    
    const ACCOUNT_STATE = [
        Account::ACTIVE, 
        Account::INACTIVE,
    ];

    const REGEX = [
        'password' => '/^[\d]{4}$/',
        'identification' => '/^[\d]{1,12}$/',
        'numbers' => '/^[\d]+$/',
        'description' => '/^[ \dA-Za-zñÑäÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙ.,-:;\(\)\/\s]+$/',
    ];

    CONST EXCEPCION_CODE = [
        'login' => 11
    ];

    /**
    * @var array => Variable que contiene la estructura de respuesta del api
    */ 
    
    const JSON_RESPONSE = [
        'result' => [
            'state' => '', // ERROR | OK
            'data' => [] // array ..
        ],
        'code' => 0, //codigo de estado http 200 | ...
    ];
}
