<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_batches', function (Blueprint $table) {
            $table->dropColumn('tier_name');
        });
    }

    public function down(): void
    {
        Schema::table('ticket_batches', function (Blueprint $table) {
            $table->string('tier_name')->default('Regular')->after('venue_zone_id');
        });
    }
};