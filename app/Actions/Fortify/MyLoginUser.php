<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\MyConfig\Globals;
use Exception;
use Illuminate\Support\Facades\Validator;

class MyLoginUser
{
    /**
     * Validate user login with identification.
     *
     * @param  array  $input
     * @return void
     */
    public static function validIdentification(array $input): ?User
    {
        $validator = Validator::make($input, 
            [
                'identification' => ['required', 'regex:'.Globals::REGEX['identification']],
            ],
        );
        if($validator->fails()){
            foreach ($validator->errors()->messages() as $key => $value) {
                throw new Exception($value[0], Globals::EXCEPCION_CODE['login']);
                return null;
            }
        }
        $user = User::where('identification', $input['identification'])->first();
        if(!$user) throw new Exception(__('auth.failed'), Globals::EXCEPCION_CODE['login']);
        return $user;

        
    }
}