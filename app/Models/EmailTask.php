<?php
/*
# Welcome to WHYSPICE OS 0.0.1 (GNU/Linux 3.13.0.129-generic x86_64)

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

class EmailTask
{
    public function create($email, $message)
    {
        $task = R::dispense('email_tasks');
        $task->email = $email;
        $task->message = $message;
        $task->status = 'pending';
        R::store($task);
        return $task->id;
    }

    public function getPendingTasks()
    {
        return R::findAll('email_tasks', 'status = ?', ['pending']);
    }

    public function markAsSent($id)
    {
        $task = R::load('email_tasks', $id);
        $task->status = 'sent';
        R::store($task);
    }
}