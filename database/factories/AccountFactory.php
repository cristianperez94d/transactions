<?php

namespace Database\Factories;

use App\Models\Account;
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

        $type = Globals::ACCOUNT_TYPE[rand(0, $maxT)];
        return [
            'account' => $this->faker->unique()->randomNumber(8),
            'current_value' => rand(10000,50000),
            'type' => $type,
            'state' => ($type === Account::TYPE_PROPIA) ? Account::ACTIVE : Globals::ACCOUNT_STATE[rand(0, $maxS)],
            'user_id' => User::where('type', '!=', User::ADMIN)->get()->random()->id,
        ];
    }
}
