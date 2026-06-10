<?php

namespace App\Http\Controllers;

use App\Support\ShareImage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ShareImageController extends Controller
{
    public function story(string $filename): BinaryFileResponse
    {
        $sourcePath = ShareImage::storySourcePath($filename);

        abort_unless($sourcePath !== null, 404);

        $cachePath = ShareImage::storyCachePath($filename);

        if (! ShareImage::ensureStoryCache($sourcePath, $cachePath)) {
            abort(404);
        }

        return response()->file($cachePath, [
            'Content-Type' => 'image/jpeg',
            'Cache-Control' => 'public, max-age=604800, immutable',
        ]);
    }
}
