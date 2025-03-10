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
namespace App\Controllers;

use App\Core\Hash;
use App\Core\UploadedFile;
use App\Core\Storage;
use App\Core\Validator;

class UserController
{
    protected function validate(array $data, array $rules, array $messages = []): void
    {
        Validator::make($data, $rules, $messages)->validate();
    }

    public function show(string $id): string
    {
        return View::render('user/profile.twig', ['id' => $id]);
    }

    public function update(string $user): string
    {
        $this->validate($_POST, [
            'username' => 'required|min:3|max:50',
            'email' => 'required|email',
        ]);

        return "User {$user} updated";
    }

    public function delete(string $user): string
    {
        return "User {$user} deleted";
    }

    public function uploadAvatar(): string
    {
        $file = UploadedFile::createFromGlobal('avatar');
        $this->validate(
            ['avatar' => $file],
            ['avatar' => 'required|file|mimes:jpg,png'],
            ['avatar.mimes' => 'The avatar must be a JPG or PNG file.']
        );

        $path = $file->store('avatars');
        $url = Storage::disk()->url($path);

        return $url;
    }

    public function create(): string
    {
        $this->validate($_POST, [
            'username' => 'required|min:3|max:20',
            'password' => 'required|min:6',
        ]);

        $username = $_POST['username'];
        $password = Hash::make($_POST['password']);

        return "User {$username} registered.";
    }
}