<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Administrador',
            'email' => 'admin@email.com',
            'identification' => '12345678',
            'type' => User::ADMIN,
            'email_verified_at' => now(),
            'password' => '$2y$10$pTR0lxrIpQlOUOdEISDZ9e7yFKDgJs15ZYe2SoOKGNd6sBMxerrY6', // 1234
            'remember_token' => Str::random(10),
        ]);
        $tokenApi = $user->createToken('appToken')->plainTextToken;
        $user->tokenApi = $tokenApi;
        $user->save();

        $users = User::factory(3)->create();

        $accounts = Account::factory()
            ->count(5)
            ->state(new Sequence(
                ['type' => Account::TYPE_PROPIA]
            ))
            ->create()
        ;

        foreach ($users as $key => $u) {
            $tokenApi = $u->createToken('appToken')->plainTextToken;
            $u->tokenApi = $tokenApi;
            $u->save();

            Account::factory()
                ->count(1)
                ->state( new Sequence(
                    [
                        'account' => Account::find($accounts->where('user_id', '!=', $u->id)->where('type', '!=', Account::TYPE_TERCEROS)->random()->id)->account,
                        'type' => Account::TYPE_TERCEROS,
                        'user_id' => $u->id,
                        'current_value' => 0,
                    ]
                ))
                ->create()
            ;
        }

        Transaction::factory(15)->create();

    }
}
