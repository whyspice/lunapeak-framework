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

class Storage
{
    protected static array $disks = [
        'public' => [
            'root' => BASE_PATH . '/storage/app/public',
            'url' => '/storage',
        ],
    ];

    public static function disk(string $disk = 'public'): self
    {
        if (!isset(self::$disks[$disk])) {
            throw new \InvalidArgumentException("Disk '{$disk}' not configured");
        }
        return new self($disk);
    }

    protected string $disk;

    protected function __construct(string $disk)
    {
        $this->disk = $disk;
    }

    public function path(string $path = ''): string
    {
        $root = self::$disks[$this->disk]['root'];
        if (!is_dir($root)) {
            mkdir($root, 0755, true);
        }
        return rtrim($root, '/') . '/' . ltrim($path, '/');
    }

    public function url(string $path): string
    {
        return rtrim(self::$disks[$this->disk]['url'], '/') . '/' . ltrim($path, '/');
    }
}