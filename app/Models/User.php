<?php
/*
# Welcome to WHYSPICE OS v0.0.1 (GNU/Linux 3.13.0.129-generic x86_64)

root@localhost:~ bash ./whyspice-work.sh
> Executing...

         _       ____  ____  _______ ____  ________________
        | |     / / / / /\ \/ / ___// __ \/  _/ ____/ ____/
        | | /| / / /_/ /  \  /\__ \/ /_/ // // /   / __/
        | |/ |/ / __  /   / /___/ / ____// // /___/ /___
        |__/|__/_/ /_/   /_//____/_/   /___/\____/_____/

                            Web Dev.
                WHYSPICE © 2025 # whyspice.su

> Disconnecting.

# Connection closed by remote host.
*/
namespace App\Models;

use R;

class User
{
    public function create($user)
    {
        $user = R::dispense('users');
        $user->username = $user['username'];
        $user->password = $user['password'];
        $user->email = $user['email'];
        $user->avatar = null;
        $user->group = null;
        $user->register_ip = null;
        $user->created_at = time();
        $user->updated_at = time();
        $user->deleted_at = null;
        R::store($user);
        return $user->id;
    }

    public function update($user)
    {
        return 'updated';
    }

    public function delete($user)
    {
        return 'deleted';
    }
}