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

use R;

class Database
{
    public static function connect()
    {
        Config::load('database');
        $config = [
            "driver" => Config::get('database.driver'),
            "host" => Config::get('database.host'),
            "database" => Config::get('database.database'),
            "username" => Config::get('database.username'),
            "password" => Config::get('database.password'),
        ];

        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        R::setup($dsn, $config['username'], $config['password']);
        R::freeze(true);

        if (!R::testConnection()) {
            View::render('/errors/error.twig', [
                'error' => [
                    'title' => 'Error!',
                    'text' => 'Could not connect to database.'
                ]
            ]);
            exit;
        }
    }
}