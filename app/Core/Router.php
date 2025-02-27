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

class Router
{
    protected array $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'PATCH' => [],
        'DELETE' => [],
    ];

    public function __construct()
    {
        $this->loadRoutes(BASE_PATH . '/routes/web.php');
        $this->loadRoutes(BASE_PATH . '/routes/api.php', '/api');
    }

    protected function loadRoutes(string $file, string $prefix = ''): void
    {
        if (file_exists($file)) {
            $router = $this;
            require $file;

            if ($prefix) {
                $this->applyPrefix($prefix);
            }
        }
    }

    protected function applyPrefix(string $prefix): void
    {
        foreach ($this->routes as $method => $routes) {
            $prefixedRoutes = [];
            foreach ($routes as $route => $handler) {
                if (!str_starts_with($route, $prefix)) {
                    $prefixedRoutes[$prefix . $route] = $handler;
                    unset($this->routes[$method][$route]);
                }
            }
            $this->routes[$method] = array_merge($this->routes[$method], $prefixedRoutes);
        }
    }

    public function get(string $route, array $handler): void
    {
        $this->routes['GET'][$route] = $handler;
    }

    public function post(string $route, array $handler): void
    {
        $this->routes['POST'][$route] = $handler;
    }

    public function put(string $route, array $handler): void
    {
        $this->routes['PUT'][$route] = $handler;
    }

    public function patch(string $route, array $handler): void
    {
        $this->routes['PATCH'][$route] = $handler;
    }

    public function delete(string $route, array $handler): void
    {
        $this->routes['DELETE'][$route] = $handler;
    }

    public function match(array $methods, string $route, array $handler): void
    {
        foreach ($methods as $method) {
            $method = strtoupper($method);
            if (array_key_exists($method, $this->routes)) {
                $this->routes[$method][$route] = $handler;
            }
        }
    }

    public function any(string $route, array $handler): void
    {
        foreach ($this->routes as $method => &$paths) {
            $paths[$route] = $handler;
        }
    }

    public function dispatch(): void
    {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $isApi = str_starts_with($uri, '/api');

        foreach ($this->routes[$method] as $route => $handler) {
            $pattern = preg_replace('/\{(\w+)\}/', '(\w+)', $route);
            $pattern = "#^$pattern$#";
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                [$controller, $action] = $handler;
                $instance = new $controller();
                $response = call_user_func_array([$instance, $action], $matches);
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

    protected function show404(bool $isApi = false): void
    {
        header('HTTP/1.0 404 Not Found');
        if ($isApi) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'API route not found']);
        } else {
            View::render('errors/404.twig');
        }
        exit;
    }
}