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
use App\Core\Storage;
use App\Core\UploadedFile;
use App\Core\Validator;
use App\Core\Request;
use App\Models\User;

class UserController
{
    protected function validate(array $data, array $rules, array $messages = []): void
    {
        Validator::make($data, $rules, $messages)->validate();
    }

    public function show(Request $request, string $id): string
    {
        $user = User::findOrFail($id);
        return View::render('user/show.twig', ['user' => $user]);
    }

    public function edit(Request $request, string $user): string
    {
        $user = User::findOrFail($user);
        return View::render('user/edit.twig', ['user' => $user]);
    }

    public function update(Request $request, string $user): string
    {
        $this->validate($request->all(), [
            'name' => 'required|min:3|max:50',
            'email' => 'required|email',
        ]);

        $user = User::findOrFail($user);
        $user->update($request->only(['name', 'email']));
        return "User {$user->id} updated";
    }

    public function delete(Request $request, string $user): string
    {
        $user = User::findOrFail($user);
        $user->delete();
        return "User {$user->id} deleted";
    }

    public function uploadAvatar(Request $request): string
    {
        $file = $request->file('avatar');
        $this->validate(
            ['avatar' => $file],
            ['avatar' => 'required|file|mimes:jpg,png'],
            ['avatar.mimes' => 'The avatar must be a JPG or PNG file.']
        );

        $path = $file->store('avatars');
        $url = Storage::disk('public')->url($path);

        $userId = $request->input('user_id');
        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                $user->update(['avatar' => $url]);
            }
        }

        return "File uploaded to: $url";
    }

    public function register(Request $request): string
    {
        $this->validate($request->all(), [
            'username' => 'required|min:3|max:20',
            'password' => 'required|min:6',
            'email' => 'required|email',
        ]);

        $user = User::create([
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
            'email' => $request->input('email'),
        ]);

        return "User {$user->username} registered with ID: {$user->id}";
    }
}