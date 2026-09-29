<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url', 1024);
            $table->string('type')->default('rss'); // rss | google_news_rss
            $table->boolean('is_active')->default(true);
            $table->json('config')->nullable();
            $table->timestamp('last_fetched_at')->nullable();
            $table->string('last_status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_sources');
    }
};
