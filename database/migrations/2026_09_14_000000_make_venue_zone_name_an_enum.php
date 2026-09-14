<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('venue_zones', function (Blueprint $table) {
            $table->enum('name', ['vip', 'front', 'middle', 'upper'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('venue_zones', function (Blueprint $table) {
            $table->string('name')->change();
        });
    }
};