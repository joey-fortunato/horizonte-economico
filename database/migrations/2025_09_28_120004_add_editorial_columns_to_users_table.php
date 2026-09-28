<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('author')->after('email'); // administrador | editor | author
            $table->string('status')->default('active')->after('role'); // active | invited | disabled
            $table->string('slug')->nullable()->unique()->after('status');
            $table->string('title')->nullable()->after('slug'); // cargo/função editorial
            $table->text('bio')->nullable()->after('title');
            $table->foreignId('avatar_media_id')->nullable()->after('bio')->constrained('media')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('avatar_media_id');
            $table->dropColumn(['role', 'status', 'slug', 'title', 'bio']);
        });
    }
};
