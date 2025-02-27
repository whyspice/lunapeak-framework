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
use App\Core\Config;

class Router
{
    protected $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'PATCH' => [],
        'DELETE' => [],
    ];

    public function get($route, $handler)
    {
        $this->routes['GET'][$route] = $handler;
    }

    public function post($route, $handler)
    {
        $this->routes['POST'][$route] = $handler;
    }

    public function put($route, $handler)
    {
        $this->routes['PUT'][$route] = $handler;
    }

    public function patch($route, $handler)
    {
        $this->routes['PATCH'][$route] = $handler;
    }

    public function delete($route, $handler)
    {
        $this->routes['DELETE'][$route] = $handler;
    }

    public function match(array $methods, $route, $handler)
    {
        foreach ($methods as $method) {
            $method = strtoupper($method);
            if (array_key_exists($method, $this->routes)) {
                $this->routes[$method][$route] = $handler;
            }
        }
    }

    public function any($route, $handler)
    {
        foreach ($this->routes as $method => &$paths) {
            $paths[$route] = $handler;
        }
    }

    public function dispatch()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $isApi = strpos($uri, '/api') === 0;

        foreach ($this->routes[$method] as $route => $handler) {
            $pattern = preg_replace('/\{(\w+)\}/', '(\w+)', $route);
            $pattern = "#^$pattern$#";
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                list($controller, $action) = explode('@', $handler);
                $controller = "App\\Controllers\\$controller";
                $response = call_user_func_array([new $controller(), $action], $matches);
                if ($isApi) {
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($response, JSON_UNESCAPED_UNICODE);
                } else {
                    View::render($response);
                }
                return;
            }
        }
        $this->show404($isApi);
    }

    protected function show404($isApi = false)
    {
        if ($isApi) {
            header('HTTP/1.0 404 Not Found');
            header('Content-Type: application/json');
            echo json_encode(['error' => 'not found']);
        } else {
            header('HTTP/1.0 404 Not Found');
            View::render('errors/404.twig');
        }
        exit;
    }
}