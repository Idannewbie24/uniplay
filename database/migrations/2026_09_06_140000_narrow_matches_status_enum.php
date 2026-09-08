<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE matches MODIFY `status` ENUM('upcoming', 'live', 'finished', 'cancelled') NOT NULL DEFAULT 'upcoming'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE matches MODIFY `status` ENUM('upcoming', 'live', 'finished', 'completed', 'cancelled') NOT NULL DEFAULT 'upcoming'");
        }
    }
};