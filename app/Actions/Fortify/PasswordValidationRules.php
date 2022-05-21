<?php

namespace App\Actions\Fortify;

use App\MyConfig\Globals;
use Laravel\Fortify\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function passwordRules()
    {
        return [
            'required',
            'regex:'.Globals::REGEX['password'],
            'confirmed'
        ];
    }
}
