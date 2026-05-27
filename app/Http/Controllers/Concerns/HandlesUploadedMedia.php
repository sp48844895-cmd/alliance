<?php

namespace App\Http\Controllers\Concerns;

use App\Support\MediaUrl;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait HandlesUploadedMedia
{
    protected function assertValidUpload(Request $request, string $field, bool $required = false): void
    {
        if ($request->hasFile($field)) {
            $file = $request->file($field);

            if (! $file->isValid()) {
                throw ValidationException::withMessages([
                    $field => $this->uploadFailureMessage($file),
                ]);
            }

            return;
        }

        $file = $request->file($field);

        if ($file instanceof UploadedFile && ! $file->isValid() && $file->getError() !== UPLOAD_ERR_NO_FILE) {
            throw ValidationException::withMessages([
                $field => $this->uploadFailureMessage($file),
            ]);
        }

        if ($required) {
            throw ValidationException::withMessages([
                $field => 'Please select an image file.',
            ]);
        }
    }

    protected function uploadFailureMessage(UploadedFile $file): string
    {
        $limit = ini_get('upload_max_filesize') ?: '2M';

        return match ($file->getError()) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => "Image is too large. Maximum upload size is {$limit}. Use a compressed JPG or PNG under this limit.",
            UPLOAD_ERR_PARTIAL => 'Upload was interrupted. Please try again.',
            UPLOAD_ERR_NO_FILE => 'Please select an image file.',
            default => 'Image upload failed. Try a JPG or PNG file under '.$limit.'.',
        };
    }

    protected function storeUploadedFile(string $preset, UploadedFile $file, ?string $basename = null, ?string $errorField = null): string
    {
        if (! $file->isValid()) {
            $field = $errorField ?: 'image';
            throw ValidationException::withMessages([
                $field => $this->uploadFailureMessage($file),
            ]);
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'jpg');
        $filename = $basename !== null && $basename !== ''
            ? $basename.'.'.$extension
            : Str::random(20).'.'.$extension;

        $destination = MediaUrl::uploadPath($preset);

        try {
            $file->move($destination, $filename);
        } catch (\Throwable) {
            $field = $errorField ?: 'image';
            throw ValidationException::withMessages([
                $field => 'Could not save the uploaded file. Check that the uploads folder is writable.',
            ]);
        }

        return $filename;
    }

    protected function replaceUploadedFile(string $preset, UploadedFile $file, ?string $oldFilename, ?string $basename = null, ?string $errorField = null): string
    {
        $this->deleteUploadedFile($preset, $oldFilename);

        return $this->storeUploadedFile($preset, $file, $basename, $errorField);
    }

    protected function deleteUploadedFile(string $preset, ?string $filename): void
    {
        MediaUrl::delete($preset, (string) $filename);
    }
}
