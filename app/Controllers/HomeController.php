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
namespace App\Controllers;

use App\Core\Request;
use function App\Core\set_locale;
use function App\Core\redirect;

class HomeController
{
    public function index(Request $request)
    {
        return 'home.twig';
    }

    public function setLocale(Request $request, string $locale): void
    {
        set_locale($locale);
        redirect($request->header('Referer', '/'));
    }
}