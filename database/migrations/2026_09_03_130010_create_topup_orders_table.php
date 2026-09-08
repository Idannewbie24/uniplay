<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topup_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('topup_product_id')->constrained();
            $table->foreignId('topup_denomination_id')->constrained();
            $table->string('game_user_id');
            $table->string('game_zone_id')->nullable();
            $table->string('game_ign')->nullable();
            $table->string('payment_channel')->default('whatsapp');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('fee', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->enum('status', ['pending', 'paid', 'delivered', 'failed'])->default('pending');
            $table->text('whatsapp_message_snapshot')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topup_orders');
    }
};
