<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Account;
use App\MyConfig\Globals;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $maxT = count(Globals::ACCOUNT_TYPE)-1;
        return [
            'type' => Globals::ACCOUNT_TYPE[rand(0, $maxT)],
            'account_id' => Account::all()->random()->id,
            'user_id' => User::where('type', '!=', User::ADMIN)->get()->random()->id,
        ];
    }
}