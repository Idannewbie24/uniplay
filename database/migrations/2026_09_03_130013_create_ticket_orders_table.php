<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('ticket_batch_id')->constrained();
            $table->integer('quantity')->default(1);
            $table->string('buyer_name');
            $table->string('buyer_phone');
            $table->decimal('total_price', 12, 2);
            $table->string('qr_code_token', 64)->unique();
            $table->enum('status', ['pending', 'paid', 'checked_in', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_orders');
    }
};
