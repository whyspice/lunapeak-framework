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
    protected string $currentPrefix = '';

    public function __construct()
    {
        $this->loadRoutes(BASE_PATH . '/routes/web.php');
        $this->loadRoutes(BASE_PATH . '/routes/api.php', '/api');
    }

    protected function loadRoutes(string $file, string $prefix = ''): void
    {
        if (file_exists($file)) {
            $router = $this;
            $originalRoutes = $this->routes;
            $this->currentPrefix = $prefix;
            require $file;
            $newRoutes = array_diff_key($this->routes, $originalRoutes);
            $this->currentPrefix = '';

            if ($prefix) {
                $this->applyPrefix($prefix, $newRoutes);
            }
        }
    }

    protected function applyPrefix(string $prefix, array &$newRoutes): void
    {
        foreach ($newRoutes as $method => $routes) {
            $prefixedRoutes = [];
            foreach ($routes as $route => $handler) {
                $prefixedRoute = rtrim($prefix, '/') . '/' . ltrim($route, '/');
                $prefixedRoutes[$prefixedRoute] = $handler;
            }
            $this->routes[$method] = array_merge($this->routes[$method], $prefixedRoutes);
        }
    }

    public function group(string $prefix, callable $callback): void
    {
        $originalPrefix = $this->currentPrefix;
        $this->currentPrefix = rtrim($originalPrefix, '/') . '/' . ltrim($prefix, '/');
        $callback($this);
        $this->currentPrefix = $originalPrefix;
    }

    public function get(string $route, array $handler): void
    {
        $fullRoute = $this->currentPrefix . $route;
        $this->routes['GET'][$fullRoute] = $handler;
    }

    public function post(string $route, array $handler): void
    {
        $fullRoute = $this->currentPrefix . $route;
        $this->routes['POST'][$fullRoute] = $handler;
    }

    public function put(string $route, array $handler): void
    {
        $fullRoute = $this->currentPrefix . $route;
        $this->routes['PUT'][$fullRoute] = $handler;
    }

    public function patch(string $route, array $handler): void
    {
        $fullRoute = $this->currentPrefix . $route;
        $this->routes['PATCH'][$fullRoute] = $handler;
    }

    public function delete(string $route, array $handler): void
    {
        $fullRoute = $this->currentPrefix . $route;
        $this->routes['DELETE'][$fullRoute] = $handler;
    }

    public function match(array $methods, string $route, array $handler): void
    {
        $fullRoute = $this->currentPrefix . $route;
        foreach ($methods as $method) {
            $method = strtoupper($method);
            if (array_key_exists($method, $this->routes)) {
                $this->routes[$method][$fullRoute] = $handler;
            }
        }
    }

    public function any(string $route, array $handler): void
    {
        $fullRoute = $this->currentPrefix . $route;
        foreach ($this->routes as $method => &$paths) {
            $paths[$fullRoute] = $handler;
        }
    }

    public function dispatch(): void
    {
        $request = Request::capture();
        $uri = $request->path();
        $method = $request->method();
        $isApi = strpos($uri, '/api') === 0;

        foreach ($this->routes[$method] as $route => $handler) {
            $pattern = preg_replace('/\{(\w+)\}/', '([^/]+)', $route);
            $pattern = "#^$pattern$#";
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                [$controller, $action] = $handler;
                $instance = new $controller();

                $reflection = new \ReflectionMethod($instance, $action);
                $parameters = $reflection->getParameters();
                $args = [];
                foreach ($parameters as $param) {
                    if ($param->getType() && $param->getType()->getName() === Request::class) {
                        $args[] = $request;
                    } else {
                        $args[] = array_shift($matches);
                    }
                }

                $response = $reflection->invokeArgs($instance, $args);
                if ($isApi) {
                    header('Content-Type: application/json');
                    echo json_encode($response);
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