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

class Config
{
    protected static $config = [];

    public static function load($file)
    {
        $path = BASE_PATH . '/config/' . $file . '.php';
        if (file_exists($path)) {
            self::$config[$file] = require $path;
        }
    }

    public static function get($key, $default = null)
    {
        list($file, $param) = explode('.', $key, 2);
        return self::$config[$file][$param] ?? $default;
    }
}