<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('cover_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->string('status')->default('draft')->index(); // draft|review|scheduled|published|archived
            $table->timestamp('published_at')->nullable()->index();
            $table->unsignedSmallInteger('reading_minutes')->default(1);
            $table->unsignedBigInteger('views_count')->default(0);
            // SEO
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->foreignId('share_media_id')->nullable()->constrained('media')->nullOnDelete();
            // Editorial
            $table->text('sources')->nullable();          // JSON de fontes/referências
            $table->text('correction_note')->nullable();  // nota de correcção
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
