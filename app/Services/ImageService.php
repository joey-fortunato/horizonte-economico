<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /** Larguras responsivas geradas (não faz upscale). */
    private array $widths = [400, 800, 1600];

    /**
     * Processa uma imagem carregada: gera variantes WebP responsivas.
     *
     * @return array{path:string,mime_type:string,width:int,height:int,variants:array<int,string>,size:int}
     */
    public function store(UploadedFile $file, string $dir = 'media', string $disk = 'public'): array
    {
        $src = $this->createImage($file);

        // Sem GD ou formato não suportado: guarda o original tal como está.
        if (! $src) {
            $path = $file->store($dir, $disk);

            return [
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'width' => 0,
                'height' => 0,
                'variants' => [],
                'size' => $file->getSize(),
            ];
        }

        $ow = imagesx($src);
        $oh = imagesy($src);
        $base = $dir.'/'.Str::lower(Str::random(20));

        $targets = array_values(array_filter($this->widths, fn ($w) => $w < $ow));
        $targets[] = min($ow, max($this->widths)); // variante "cheia" (até 1600)
        $targets = array_values(array_unique($targets));
        sort($targets);

        $variants = [];
        $largestPath = null;
        $largestSize = 0;

        foreach ($targets as $w) {
            $h = (int) round($oh * ($w / $ow));
            $resized = imagescale($src, $w, $h);
            if (! $resized) {
                continue;
            }
            $tmp = tempnam(sys_get_temp_dir(), 'webp');
            imagewebp($resized, $tmp, 82);
            imagedestroy($resized);

            $path = "{$base}-{$w}.webp";
            Storage::disk($disk)->put($path, file_get_contents($tmp));
            $bytes = filesize($tmp);
            @unlink($tmp);

            $variants[$w] = $path;
            if ($w >= $largestSize) {
                $largestSize = $w;
                $largestPath = $path;
            }
        }

        imagedestroy($src);

        return [
            'path' => $largestPath,
            'mime_type' => 'image/webp',
            'width' => $ow,
            'height' => $oh,
            'variants' => $variants,
            'size' => $largestPath ? Storage::disk($disk)->size($largestPath) : $file->getSize(),
        ];
    }

    private function createImage(UploadedFile $file): ?\GdImage
    {
        if (! function_exists('imagecreatefromstring')) {
            return null;
        }
        $data = @file_get_contents($file->getRealPath());
        if ($data === false) {
            return null;
        }
        $img = @imagecreatefromstring($data);

        return $img ?: null;
    }
}
