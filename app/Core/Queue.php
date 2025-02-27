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
namespace App\Core;

use Predis\Client;

class Queue
{
    protected $redis;

    public function __construct()
    {
        $config = require_once BASE_PATH . '/config/queue.php';
        $this->redis = new Client($config['redis']);
    }

    public function push($queue, $job)
    {
        $this->redis->rpush($queue, serialize($job));
    }

    public function pop($queue)
    {
        $job = $this->redis->lpop($queue);
        return $job ? unserialize($job) : null;
    }
}