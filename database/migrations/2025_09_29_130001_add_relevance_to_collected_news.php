<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('collected_news', function (Blueprint $table) {
            $table->unsignedSmallInteger('relevance_score')->default(0)->index()->after('editorial_status');
            $table->json('relevance_terms')->nullable()->after('relevance_score');
        });
    }

    public function down(): void
    {
        Schema::table('collected_news', function (Blueprint $table) {
            $table->dropColumn(['relevance_score', 'relevance_terms']);
        });
    }
};
