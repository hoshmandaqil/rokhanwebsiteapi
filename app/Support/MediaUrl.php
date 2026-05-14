<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Throwable;

final class MediaUrl
{
    public static function toAbsolute(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $normalizedPath = ltrim($path, '/');

        if (str_starts_with($normalizedPath, 'storage/')) {
            return url('/'.$normalizedPath);
        }

        if (Storage::disk('public')->exists($normalizedPath)) {
            return url(Storage::disk('public')->url($normalizedPath));
        }

        if (Storage::disk('local')->exists($normalizedPath)) {
            try {
                return Storage::disk('local')->temporaryUrl($normalizedPath, now()->addMinutes(30));
            } catch (Throwable) {
                return null;
            }
        }

        return null;
    }
}
