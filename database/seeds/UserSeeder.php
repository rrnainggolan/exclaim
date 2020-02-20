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
                'name' => 'Aristo Getriadi',
                'email' => 'aristo.getriadi@arnocindonesia.com',
                'password' => Hash::make('123456')
            ],
            [
                'name' => 'Coku Sembiring',
                'email' => 'coku.sembiring@arnocindonesia.com',
                'password' => Hash::make('123456')
            ],
            [
                'name' => 'Nopay',
                'email' => 'nopay@arnocindonesia.com',
                'password' => Hash::make('123456')
            ],
            [
                'name' => 'Ricardo Nainggolan',
                'email' => 'ricardo.nainggolan@arnocindonesia.com',
                'password' => Hash::make('123456')
            ],
            [
                'name' => 'User',
                'email' => 'user@arnocindonesia.com',
                'password' => Hash::make('123456')
            ],
        ];

        foreach($users as $user) {
            User::create($user);
        }
    }
}
