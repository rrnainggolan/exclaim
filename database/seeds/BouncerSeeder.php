<?php

use Illuminate\Database\Seeder;
use App\User;
use App\ExpenseClaim;

class BouncerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1; $i <= 3; $i++) {
            $admin = User::find($i);
            $admin->assign('admin');
        }

        $user = User::find(4);
        $user->assign('user');

        $user = User::find(5);
        $user->assign('user');

        $user = User::find(6);
        $user->assign('reviewer');

        Bouncer::allow('admin')->everything();
        Bouncer::allow('reviewer')->to('view-approved-claims');
        //Bouncer::forbid('admin')->toOwn(ExpenseClaim::class)->to('approve');
        Bouncer::allowEveryone()->toOwn(ExpenseClaim::class)->to('view');

    }
}
