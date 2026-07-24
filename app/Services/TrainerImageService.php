<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TrainerImageService
{
    public const PROFILE_DIR = 'trainers/profile';

    public const BACKGROUND_DIR = 'trainers/background';

    public const PROFILE_MAX_WIDTH = 600;

    public const BACKGROUND_MAX_WIDTH = 1600;

    public function storeProfile(UploadedFile $file): string
    {
        return $this->storeOptimized($file, self::PROFILE_DIR, self::PROFILE_MAX_WIDTH);
    }

    public function storeBackground(UploadedFile $file): string
    {
        return $this->storeOptimized($file, self::BACKGROUND_DIR, self::BACKGROUND_MAX_WIDTH);
    }

    public function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function storeOptimized(UploadedFile $file, string $directory, int $maxWidth): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        if (! in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            $extension = 'jpg';
        }

        $filename = Str::uuid()->toString().'.'.$extension;
        $relativePath = $directory.'/'.$filename;

        $optimized = $this->optimizeImage($file->getRealPath(), $extension, $maxWidth);

        if ($optimized !== null) {
            Storage::disk('public')->put($relativePath, $optimized);
        } else {
            Storage::disk('public')->putFileAs($directory, $file, $filename);
        }

        return $relativePath;
    }

    private function optimizeImage(string $sourcePath, string $extension, int $maxWidth): ?string
    {
        if (! function_exists('imagecreatefromstring')) {
            return null;
        }

        $binary = @file_get_contents($sourcePath);
        if ($binary === false) {
            return null;
        }

        $image = @imagecreatefromstring($binary);
        if ($image === false) {
            return null;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        if ($width > $maxWidth) {
            $newHeight = (int) round(($maxWidth / $width) * $height);
            $resized = imagecreatetruecolor($maxWidth, $newHeight);

            if (in_array($extension, ['png', 'webp'], true)) {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
            }

            imagecopyresampled($resized, $image, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        ob_start();

        match ($extension) {
            'png' => imagepng($image, null, 7),
            'webp' => function_exists('imagewebp') ? imagewebp($image, null, 82) : imagejpeg($image, null, 82),
            default => imagejpeg($image, null, 82),
        };

        imagedestroy($image);

        $contents = ob_get_clean();

        return $contents === false ? null : $contents;
    }
}
