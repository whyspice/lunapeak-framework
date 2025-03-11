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

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class View
{
    protected static $twig;

    public static function init(): void
    {
        $loader = new FilesystemLoader(BASE_PATH . '/views');
        self::$twig = new Environment($loader);

        self::$twig->addGlobal('app_name', Config::get('APP_NAME'));
        self::$twig->addGlobal('app_version', Config::get('APP_VERSION'));
        self::$twig->addGlobal('app_url', Config::get('APP_URL'));
        self::$twig->addGlobal('app_debug', Config::get('APP_DEBUG'));
        self::$twig->addGlobal('unixtime', time());

        self::$twig->addFunction(new TwigFunction('csrf', function () {
            return '<input type="hidden" name="_csrf" value="' . htmlspecialchars(Csrf::generateToken()) . '">';
        }));
        self::$twig->addFunction(new \Twig\TwigFunction('asset', 'App\Core\asset'));
        self::$twig->addFunction(new \Twig\TwigFunction('route', 'App\Core\route'));
        self::$twig->addFunction(new \Twig\TwigFunction('trans', 'App\Core\trans'));
        self::$twig->addFunction(new \Twig\TwigFunction('session', 'App\Core\session'));
        self::$twig->addFunction(new \Twig\TwigFunction('config', 'App\Core\config'));
        self::$twig->addFunction(new \Twig\TwigFunction('get_locale', 'App\Core\get_locale'));
    }

    public static function render($template, $data = [])
    {
        if (!self::$twig) {
            self::init();
        }

        try {
            echo self::$twig->render($template, $data);
        } catch (\Twig\Error\LoaderError $e) {
            header("HTTP/1.0 404 Not Found");
            echo self::$twig->render('errors/error.twig', ["error" => "error_404"]);
            exit;
        }
    }
}