<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('collected_news', function (Blueprint $table) {
            // guids do Google News excedem 255 caracteres
            $table->string('external_id', 1024)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('collected_news', function (Blueprint $table) {
            $table->string('external_id', 255)->nullable()->change();
        });
    }
};
