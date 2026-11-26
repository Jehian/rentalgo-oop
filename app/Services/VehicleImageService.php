<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class VehicleImageService
{
    public function store(UploadedFile $file): string
    {
        $path = $file->store($this->folder(), $this->disk());

        if (! is_string($path) || $path === '') {
            throw new RuntimeException('Gagal menyimpan gambar kendaraan.');
        }

        return $path;
    }

    public function delete(?string $path): void
    {
        if ($path === null || $path === '') {
            return;
        }

        Storage::disk($this->disk())->delete($path);
    }

    private function disk(): string
    {
        return (string) config('rental.image_disk', 'public');
    }

    private function folder(): string
    {
        return trim((string) config('rental.image_folder', 'vehicles'), '/');
    }
}
