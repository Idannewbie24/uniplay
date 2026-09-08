<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained()->cascadeOnDelete();
            $table->foreignId('venue_zone_id')->nullable()->constrained()->nullOnDelete();
            $table->string('tier_name')->default('Regular')->comment('Grandstand, Regular, VIP');
            $table->decimal('price', 12, 2);
            $table->integer('seats_total')->default(0);
            $table->integer('seats_remaining')->default(0);
            $table->string('status_badge')->nullable()->comment('e.g. Selling Fast, Limited Seats');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_batches');
    }
};
