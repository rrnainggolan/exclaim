<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserService
{
    /**
     * Get all users
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getUsers()
    {
        $users = User::all();

        return $users;
    }

    /**
     * Create new user
     * 
     * @return App\User
     */
    public function createUser($userData)
    {
        $role = $userData['role'];
        unset($userData['role']);
        //$password = $this->randomString(12);
        $password = '123456';
        $userData['password'] = Hash::make($password);

        $user = User::create($userData);
        $user->assign($role);

        return $user;
    }

    /**
     * Update selected user
     * 
     * @return App\User
     */
    public function updateUser($user, $data)
    {
        $roleOld = $user->roles[0]->name;
        $role = $data['role'];

        unset($data['role']);

        $user->update($data);

        if($roleOld != $role) {
            $user->retract($roleOld);
            $user->assign($role);
        }

        return $user;
    }

    /**
     * Delete user
     * 
     * @return int
     */
    public function deleteUser(User $user)
    {
        $id = $user->id;
        $user->delete();

        return $id;
    }

    private function randomString($length)
    {
        $keySpace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = '';
        $max = mb_strlen($keySpace, '8bit') - 1;
        if($max < 1) {
            throw new Exception($keySpace . ' must be at least two characters long');
        }
        for($i = 0; $i < $length; ++$i) {
            $str .= $keySpace[random_int(0, $max)];
        }
        return $str;
    }
}