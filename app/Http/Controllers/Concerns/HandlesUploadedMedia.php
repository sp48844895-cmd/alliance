<?php

namespace App\Http\Controllers\Concerns;

use App\Support\MediaUrl;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

trait HandlesUploadedMedia
{
    protected function storeUploadedFile(string $preset, UploadedFile $file, ?string $basename = null): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = $basename !== null && $basename !== ''
            ? $basename.'.'.$extension
            : Str::random(20).'.'.$extension;

        $file->move(MediaUrl::uploadPath($preset), $filename);

        return $filename;
    }

    protected function replaceUploadedFile(string $preset, UploadedFile $file, ?string $oldFilename, ?string $basename = null): string
    {
        $this->deleteUploadedFile($preset, $oldFilename);

        return $this->storeUploadedFile($preset, $file, $basename);
    }

    protected function deleteUploadedFile(string $preset, ?string $filename): void
    {
        MediaUrl::delete($preset, (string) $filename);
    }
}
