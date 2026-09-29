<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_fetch_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_source_id')->nullable()->constrained('news_sources')->nullOnDelete();
            $table->string('status')->default('pending'); // pending|running|completed|completed_with_errors|failed
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->unsignedInteger('items_found')->default(0);
            $table->unsignedInteger('items_created')->default(0);
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_fetch_runs');
    }
};
