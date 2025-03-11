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

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Connection;
use PDOException;

class Database
{
    protected static ?Capsule $capsule = null;

    public static function connect(): void
    {
        if (self::$capsule === null) {
            try {
                $capsule = new Capsule();

                $driver = Config::get('DB_CONNECTION', 'mysql');
                $host = Config::get('DB_HOST');
                $database = Config::get('DB_DATABASE');
                $username = Config::get('DB_USERNAME', '');
                $password = Config::get('DB_PASSWORD', '');

                if (!$driver || !$host || !$database) {
                    throw new \RuntimeException('Invalid database configuration');
                }

                $capsule->addConnection([
                    'driver' => $driver,
                    'host' => $host,
                    'database' => $database,
                    'username' => $username,
                    'password' => $password,
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix' => '',
                ]);

                $capsule->setAsGlobal();
                $capsule->bootEloquent();

                $capsule->getConnection()->getPdo();
                self::$capsule = $capsule;
            } catch (PDOException $e) {
                View::render('/errors/error.twig', [
                    'error' => [
                        'title' => 'Database Error!',
                        'text' => 'Could not connect to database: ' . $e->getMessage(),
                    ]
                ]);
                exit;
            } catch (\RuntimeException $e) {
                View::render('/errors/error.twig', [
                    'error' => [
                        'title' => 'Configuration Error!',
                        'text' => $e->getMessage(),
                    ]
                ]);
                exit;
            }
        }
    }

    public static function getCapsule(): Capsule
    {
        self::connect();
        return self::$capsule;
    }
}