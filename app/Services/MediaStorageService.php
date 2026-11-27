<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Format;
use Intervention\Image\Laravel\Facades\Image;
use Throwable;

class MediaStorageService
{
    /** Use generated names and fixed folders; never trust a client filename as a path. */
    public function store(UploadedFile $file, string $folder, bool $image = false): string
    {
        $name = Str::uuid()->toString().'.'.($image ? 'webp' : $file->extension());
        $path = trim($folder, '/').'/'.$name;
        $disk = Storage::disk('documents');
        if ($image) {
            $disk->put($path, (string) Image::decode($file)->encodeUsingFormat(Format::WEBP, quality: 85));
        } else {
            $disk->putFileAs($folder, $file, $name);
        }

        return 'documents/'.$path;
    }

    /** Cleanup after persistence is best-effort; a cleanup failure cannot undo the saved replacement. */
    public function delete(?string $path): void
    {
        if (! $path || ! str_starts_with($path, 'documents/') || str_contains($path, '..')) {
            return;
        }
        try {
            Storage::disk('documents')->delete(substr($path, strlen('documents/')));
        } catch (Throwable $exception) {
            try {
                report($exception);
            } catch (Throwable) {
            }
        }
    }
}
