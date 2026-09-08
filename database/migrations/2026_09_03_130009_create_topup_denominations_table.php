<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topup_denominations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topup_product_id')->constrained()->cascadeOnDelete();
            $table->string('label')->comment('e.g. 257 Diamonds');
            $table->integer('base_amount')->default(0);
            $table->integer('bonus_amount')->default(0);
            $table->decimal('price', 12, 2);
            $table->string('badge')->nullable()->comment('e.g. BEST VALUE, SEASON PASS');
            $table->string('type', 50)->default('diamond');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topup_denominations');
    }
};
