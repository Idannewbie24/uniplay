<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shorts', function (Blueprint $table) {
            $table->string('video_path')->nullable()->after('video_url');
            $table->text('description')->nullable()->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('shorts', function (Blueprint $table) {
            $table->dropColumn(['video_path', 'description']);
        });
    }
};
