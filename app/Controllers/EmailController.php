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
namespace App\Controllers;

use App\Core\Queue;
use App\Models\EmailTask;

class EmailController
{
    public function showForm()
    {
        return 'email_form.twig';
    }

    public function sendEmail()
    {
        $email = $_POST['email'];
        $message = $_POST['message'];
        $taskModel = new EmailTask();
        $taskId = $taskModel->create($email, $message);
        $queue = new Queue();
        $queue->push('email_queue', ['task_id' => $taskId]);
        echo "Задача на отправку email добавлена в очередь.";
    }
}