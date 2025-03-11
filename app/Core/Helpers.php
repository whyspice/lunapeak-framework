<?php
/*
# Welcome to WHYSPICE OS v0.0.1 (GNU/Linux 3.13.0.129-generic x86_64)

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

function route(string $name, array $params = []): string
{
    $router = new Router();
    $routes = array_merge(
        $router->routes['GET'],
        $router->routes['POST'],
        $router->routes['PUT'],
        $router->routes['PATCH'],
        $router->routes['DELETE']
    );

    foreach ($routes as $route => [$handler, $middleware]) {
        if ($handler[1] === $name) {
            $url = $route;
            foreach ($params as $key => $value) {
                $url = str_replace("{{$key}}", $value, $url);
            }
            return rtrim(BASE_URL, '/') . '/' . ltrim($url, '/');
        }
    }
    return '';
}

function asset(string $path): string
{
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

function session(string $key, mixed $default = null): mixed
{
    return $_SESSION[$key] ?? $default;
}

function set_session(string $key, mixed $value): void
{
    $_SESSION[$key] = $value;
}

function config(string $key, mixed $default = null): mixed
{
    return Config::get($key, $default);
}

function redirect(string $url): never
{
    header("Location: $url");
    exit;
}

function trans(string $key, array $replace = [], string $locale = null): string
{
    $locale = $locale ?? session('locale', config('APP_LOCALE', 'en'));
    $translations = file_exists(BASE_PATH . "/lang/{$locale}/messages.php")
        ? require BASE_PATH . "/lang/{$locale}/messages.php"
        : [];

    $translation = $translations[$key] ?? $key;

    foreach ($replace as $param => $value) {
        $translation = str_replace(":{$param}", $value, $translation);
    }

    return $translation;
}

function set_locale(string $locale): void
{
    set_session('locale', $locale);
}

function get_locale(): string
{
    return session('locale');
}