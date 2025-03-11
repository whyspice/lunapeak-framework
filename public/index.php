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
define('BASE_PATH', realpath(__DIR__ . '/../'));
require_once BASE_PATH . '/vendor/autoload.php';

use App\Core\Database;
use App\Core\Config;
use App\Core\Router;
use App\Core\View;
use Tracy\Debugger;

session_start();
Config::load();
define('BASE_URL', Config::get('APP_URL', 'http://localhost'));

if (Config::get('APP_DEBUG', false) == 'true') {
    Debugger::enable(Debugger::DEVELOPMENT);
} else {
    Debugger::enable(Debugger::PRODUCTION);
    Debugger::$showBar = false;
}

//Database::connect();
View::init();

$router = new Router();
$router->dispatch();