<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collected_news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_source_id')->nullable()->constrained('news_sources')->nullOnDelete();
            $table->string('title');
            $table->string('url', 1024);
            $table->string('normalized_url', 1024);
            $table->string('external_id')->nullable();
            $table->string('source_name')->nullable(); // fonte original (ex.: no Google News)
            $table->text('description')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('fetched_at');
            $table->string('editorial_status')->default('pending')->index();
            $table->timestamps();

            $table->unique('normalized_url');
            $table->index('external_id');
            $table->index('published_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collected_news');
    }
};
