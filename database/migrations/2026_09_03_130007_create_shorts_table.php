<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shorts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('video_url')->nullable();
            $table->string('thumbnail')->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->integer('views_count')->default(0);
            $table->string('creator_name')->nullable();
            $table->string('category_tag')->nullable()->comment('e.g. clutch, ace, mvp');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shorts');
    }
};
