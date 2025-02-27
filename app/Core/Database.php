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

use \RedBeanPHP\R as R;

class Database
{
    public static function connect(): void
    {
        $driver = Config::get('DB_CONNECTION');
        $host = Config::get('DB_HOST');
        $database = Config::get('DB_DATABASE');
        $username = Config::get('DB_USERNAME', '');
        $password = Config::get('DB_PASSWORD', '');

        if (!$driver || !$host || !$database) {
            throw new \RuntimeException('Invalid database configuration');
        }

        $dsn = "{$driver}:host={$host};dbname={$database}";
        R::setup($dsn, $username, $password);
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