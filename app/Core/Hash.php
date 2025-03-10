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

class Hash
{
    public static function make(string $value, array $options = []): string
    {
        $cost = $options['cost'] ?? 10;
        return password_hash($value, PASSWORD_BCRYPT, ['cost' => $cost]);
    }

    public static function check(string $value, string $hashedValue): bool
    {
        return password_verify($value, $hashedValue);
    }

    public static function needsRehash(string $hashedValue, array $options = []): bool
    {
        $cost = $options['cost'] ?? 10;
        return password_needs_rehash($hashedValue, PASSWORD_BCRYPT, ['cost' => $cost]);
    }
}