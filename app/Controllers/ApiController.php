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

class ApiController
{
    public function getData()
    {
        return [
            'status' => 'success',
            'data' => ['message' => 'Это данные из API']
        ];
    }

    public function createData()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        return [
            'status' => 'success',
            'data' => ['received' => $input]
        ];
    }
}