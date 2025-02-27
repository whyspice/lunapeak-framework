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
require_once BASE_PATH . '/vendor/autoload.php';

use App\Core\Queue;
use App\Models\EmailTask;

$queue = new Queue();

while (true) {
    $job = $queue->pop('email_queue');
    if ($job) {
        $taskId = $job['task_id'];
        $taskModel = new EmailTask();
        $task = R::load('email_tasks', $taskId);
        echo "Отправка email на {$task->email} с сообщением: {$task->message}\n";
        $taskModel->markAsSent($taskId);
    } else {
        sleep(1);
    }
}