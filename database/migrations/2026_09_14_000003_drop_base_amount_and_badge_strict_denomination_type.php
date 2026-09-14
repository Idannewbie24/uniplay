<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('topup_denominations', function (Blueprint $table) {
            $table->dropColumn(['base_amount', 'badge']);
            $table->enum('type', ['diamonds', 'weekly_pass', 'points'])->default('diamonds')->change();
        });
    }

    public function down(): void
    {
        Schema::table('topup_denominations', function (Blueprint $table) {
            $table->integer('base_amount')->default(0)->after('label');
            $table->string('badge')->nullable()->after('price');
            $table->string('type', 50)->default('diamond')->change();
        });
    }
};