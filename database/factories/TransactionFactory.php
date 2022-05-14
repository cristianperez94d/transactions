<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::all()->random();
        return [
            'origin_id' => $user->accounts->random()->id,
            'destiny_id' => $user->accounts->random()->id,
            'value' => rand(20000, 40000),
            'description' => $this->faker->sentence(2),
            'user_id' => $user->id,
        ];
    }
}
