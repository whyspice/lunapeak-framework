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
/** @var App\Core\Router $router */
use App\Core\Router;

use App\Controllers\HomeController;
use App\Controllers\UserController;

use App\Middleware\AuthMiddleware;

$router->get('/', [HomeController::class, 'index']);

$router->get('/locale/{locale}', [HomeController::class, 'setLocale'], 'setLocale');

$router->middleware(AuthMiddleware::class)->group('/users', function (Router $router) {
    $router->get('/{id}', [UserController::class, 'show']);
    $router->post('/create', [UserController::class, 'create']);
    $router->post('/upload-avatar', [UserController::class, 'uploadAvatar']);
    $router->patch('/{user}/update', [UserController::class, 'update']);
    $router->delete('/{user}/delete', [UserController::class, 'delete']);
});