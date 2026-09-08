<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        $tables = [
            'match_predictions',
            'prize_codes',
            'topup_orders',
            'ticket_orders',
            'shorts',
            'standings',
            'ticket_batches',
            'topup_denominations',
            'topup_products',
            'matches',
            'tournaments',
            'venue_zones',
            'venue_faqs',
            'banners',
            'site_contents',
            'site_settings',
            'venues',
            'teams',
            'games',
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function down(): void
    {
        // Data truncation is not reversible
    }
};
