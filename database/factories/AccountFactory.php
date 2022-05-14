<?php

namespace Database\Factories;

use App\Models\User;
use App\MyConfig\Globals;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $maxT = count(Globals::ACCOUNT_TYPE)-1;
        $maxS = count(Globals::ACCOUNT_STATE)-1;
        return [
            'current_value' => rand(10000,50000),
            'type' => Globals::ACCOUNT_TYPE[rand(0, $maxT)],
            'state' => Globals::ACCOUNT_STATE[rand(0, $maxS)],
            'user_id' => User::all()->random()->id,
        ];
    }
}
