<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'disk', 'path', 'alt_text', 'mime_type', 'size', 'width', 'height', 'variants', 'uploaded_by',
    ];

    protected $casts = [
        'variants' => 'array',
    ];

    public function url(): ?string
    {
        return $this->path ? Storage::disk($this->disk ?? 'public')->url($this->path) : null;
    }

    /** srcset responsivo a partir das variantes WebP. */
    public function srcset(): ?string
    {
        if (empty($this->variants)) {
            return null;
        }
        $disk = Storage::disk($this->disk ?? 'public');
        $parts = [];
        foreach ($this->variants as $width => $path) {
            $parts[] = $disk->url($path).' '.$width.'w';
        }

        return implode(', ', $parts);
    }
}
