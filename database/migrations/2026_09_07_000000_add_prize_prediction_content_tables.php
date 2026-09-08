<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('site_contents', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('rule')->comment('rule = rulebook entry, legal = legal document');
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('body');
            $table->unsignedInteger('order_index')->default(0);
            $table->timestamps();
        });

        Schema::create('match_predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('team', ['a', 'b']);
            $table->timestamps();
            $table->unique(['match_id', 'user_id']);
        });

        Schema::create('prize_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->nullOnDelete();
            $table->string('order_type')->default('topup')->comment('topup or ticket');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('code', 16)->unique();
            $table->string('prize')->nullable();
            $table->string('status')->default('pending')->comment('pending, won, claimed');
            $table->timestamp('claimed_at')->nullable();
            $table->timestamps();

            $table->index(['order_type', 'order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prize_codes');
        Schema::dropIfExists('match_predictions');
        Schema::dropIfExists('site_contents');
        Schema::dropIfExists('site_settings');
    }
};