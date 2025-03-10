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

class UploadedFile
{
    protected array $file;

    public function __construct(array $file)
    {
        $this->file = $file;
    }

    public static function createFromGlobal(string $key): ?self
    {
        if (isset($_FILES[$key]) && $_FILES[$key]['error'] !== UPLOAD_ERR_NO_FILE) {
            return new self($_FILES[$key]);
        }
        return null;
    }

    public function getClientOriginalName(): string
    {
        return $this->file['name'] ?? '';
    }

    public function getSize(): int
    {
        return (int)($this->file['size'] ?? 0);
    }

    public function getMimeType(): string
    {
        return $this->file['type'] ?? '';
    }

    public function store(string $path = '', string $disk = 'public'): string
    {
        if ($this->file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('File upload error: ' . $this->file['error']);
        }

        $destination = Storage::disk($disk)->path($path);
        $filename = $this->generateFilename();
        $fullPath = $destination . '/' . $filename;

        if (!move_uploaded_file($this->file['tmp_name'], $fullPath)) {
            throw new \RuntimeException('Failed to move uploaded file');
        }

        return $path . '/' . $filename;
    }

    protected function generateFilename(): string
    {
        return uniqid() . '_' . $this->getClientOriginalName();
    }

    public function isValid(): bool
    {
        return $this->file['error'] === UPLOAD_ERR_OK && is_uploaded_file($this->file['tmp_name']);
    }
}