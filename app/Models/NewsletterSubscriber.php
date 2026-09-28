<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    protected $fillable = [
        'email', 'status', 'source', 'consented_at', 'confirmed_at', 'unsubscribed_at',
    ];

    protected $casts = [
        'consented_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];
}
