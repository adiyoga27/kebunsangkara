<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

trait HandlesImageUploads
{
    /**
     * Resolve the new image path from the request: a fresh upload, a removal, or the current path.
     */
    protected function resolveImagePath(FormRequest $request, string $directory, ?string $currentPath = null): ?string
    {
        if ($request->hasFile('image')) {
            $this->deleteImage($currentPath);

            return $request->file('image')->store($directory, 'public');
        }

        if ($request->boolean('remove_image')) {
            $this->deleteImage($currentPath);

            return null;
        }

        return $currentPath;
    }

    protected function deleteImage(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
