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
    protected Client $redis;

    public function __construct()
    {
        $host = Config::get('REDIS_HOST');
        $port = Config::get('REDIS_PORT');
        $database = Config::get('REDIS_DATABASE', 0);

        if (!$host || !$port) {
            throw new \RuntimeException('Invalid Redis configuration');
        }

        $this->redis = new Client([
            'host' => $host,
            'port' => (int) $port,
            'database' => (int) $database,
        ]);
    }

    public function push(string $queue, mixed $job): void
    {
        if (empty($queue)) {
            throw new \InvalidArgumentException('Queue name cannot be empty');
        }
        $this->redis->rpush($queue, (array)serialize($job));
    }

    public function pop(string $queue): mixed
    {
        if (empty($queue)) {
            throw new \InvalidArgumentException('Queue name cannot be empty');
        }
        $job = $this->redis->lpop($queue);
        return $job ? unserialize($job) : null;
    }
}