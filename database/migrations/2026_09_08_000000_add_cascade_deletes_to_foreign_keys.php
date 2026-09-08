<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropForeign(['team_a_id']);
            $table->foreign('team_a_id')->references('id')->on('teams')->cascadeOnDelete();
        });

        Schema::table('matches', function (Blueprint $table) {
            $table->dropForeign(['team_b_id']);
            $table->foreign('team_b_id')->references('id')->on('teams')->cascadeOnDelete();
        });

        Schema::table('ticket_orders', function (Blueprint $table) {
            $table->dropForeign(['ticket_batch_id']);
            $table->foreign('ticket_batch_id')->references('id')->on('ticket_batches')->cascadeOnDelete();
        });

        Schema::table('topup_orders', function (Blueprint $table) {
            $table->dropForeign(['topup_denomination_id']);
            $table->foreign('topup_denomination_id')->references('id')->on('topup_denominations')->cascadeOnDelete();
        });

        Schema::table('topup_orders', function (Blueprint $table) {
            $table->dropForeign(['topup_product_id']);
            $table->foreign('topup_product_id')->references('id')->on('topup_products')->cascadeOnDelete();
        });

        Schema::table('standings', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->foreign('team_id')->references('id')->on('teams')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropForeign(['team_a_id']);
            $table->foreign('team_a_id')->references('id')->on('teams');
            $table->dropForeign(['team_b_id']);
            $table->foreign('team_b_id')->references('id')->on('teams');
        });

        Schema::table('ticket_orders', function (Blueprint $table) {
            $table->dropForeign(['ticket_batch_id']);
            $table->foreign('ticket_batch_id')->references('id')->on('ticket_batches');
        });

        Schema::table('topup_orders', function (Blueprint $table) {
            $table->dropForeign(['topup_denomination_id']);
            $table->foreign('topup_denomination_id')->references('id')->on('topup_denominations');
            $table->dropForeign(['topup_product_id']);
            $table->foreign('topup_product_id')->references('id')->on('topup_products');
        });

        Schema::table('standings', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->foreign('team_id')->references('id')->on('teams');
        });
    }
};
