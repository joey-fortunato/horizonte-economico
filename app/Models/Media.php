<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'disk', 'path', 'alt_text', 'mime_type', 'size', 'width', 'height', 'uploaded_by',
    ];

    public function url(): ?string
    {
        return $this->path ? Storage::disk($this->disk ?? 'public')->url($this->path) : null;
    }
}
