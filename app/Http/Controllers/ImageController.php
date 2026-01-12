<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImageController extends Controller
{
    /**
     * Serve images from S3 through Laravel
     */
    public function serve(Request $request, string $path)
    {
        // Decode the URL-encoded path
        $path = urldecode($path);

        // Check if file exists in S3
        if (!Storage::disk('s3')->exists($path)) {
            abort(404, 'Image not found');
        }

        // Stream the file from S3
        return Storage::disk('s3')->response($path, null, [
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
}
