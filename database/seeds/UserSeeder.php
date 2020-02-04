<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Ricardo Nainggolan',
                'email' => 'ricardo.nainggolan@arnocindonesia.com',
                'password' => Hash::make('123456')
            ]
        ];

        foreach($users as $user) {
            User::create($user);
        }
    }
}
