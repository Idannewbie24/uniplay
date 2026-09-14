<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\TopupDenomination;
use App\Models\TopupProduct;
use Illuminate\Database\Seeder;

class TopupSeeder extends Seeder
{
    public function run(): void
    {
        $mlbb = Game::where('slug', 'mobile-legends')->first();
        $ff = Game::where('slug', 'free-fire')->first();
        $valorant = Game::where('slug', 'valorant')->first();
        $fc25 = Game::where('slug', 'ea-fc-25')->first();

        $products = [
            // Mobile Legends
            ['game_id' => $mlbb->id, 'name' => 'MLBB Diamonds', 'description' => 'Top up Diamonds untuk Mobile Legends: Bang Bang', 'server_region' => 'ID', 'fulfillment_method' => 'API Auto', 'is_official_partner' => true, 'rating' => 4.9],
            ['game_id' => $mlbb->id, 'name' => 'MLBB Weekly Pass', 'description' => 'Weekly Diamond Pass untuk bonus diamond harian', 'server_region' => 'ID', 'fulfillment_method' => 'API Auto', 'is_official_partner' => true, 'rating' => 4.8],

            // Free Fire
            ['game_id' => $ff->id, 'name' => 'FF Diamonds', 'description' => 'Top up Diamond untuk Free Fire', 'server_region' => 'ID', 'fulfillment_method' => 'API Auto', 'is_official_partner' => true, 'rating' => 4.7],
            ['game_id' => $ff->id, 'name' => 'FF Membership', 'description' => 'Monthly and Weekly Membership untuk Free Fire', 'server_region' => 'ID', 'fulfillment_method' => 'API Auto', 'is_official_partner' => false, 'rating' => 4.5],

            // Valorant
            ['game_id' => $valorant->id, 'name' => 'Valorant Points', 'description' => 'Top up VP untuk Valorant', 'server_region' => 'SEA', 'fulfillment_method' => 'API Auto', 'is_official_partner' => true, 'rating' => 4.8],
            ['game_id' => $valorant->id, 'name' => 'Night Market Credits', 'description' => 'Bonus Credits untuk Night Market', 'server_region' => 'SEA', 'fulfillment_method' => 'Manual', 'is_official_partner' => false, 'rating' => 4.3],

            // EA FC 25
            ['game_id' => $fc25->id, 'name' => 'FC Points', 'description' => 'Top up FC Points untuk EA FC 25', 'server_region' => 'Global', 'fulfillment_method' => 'Manual', 'is_official_partner' => false, 'rating' => 4.2],
        ];

        foreach ($products as $product) {
            TopupProduct::create($product);
        }

        // MLBB Diamonds denominations
        $mlbbDiamonds = TopupProduct::where('name', 'MLBB Diamonds')->first();
        $diamondDenoms = [
            ['label' => '56 Diamonds', 'bonus_amount' => 0, 'price' => 15000, 'type' => 'diamonds'],
            ['label' => '85 Diamonds', 'bonus_amount' => 0, 'price' => 22000, 'type' => 'diamonds'],
            ['label' => '172 Diamonds', 'bonus_amount' => 5, 'price' => 43000, 'type' => 'diamonds'],
            ['label' => '257 Diamonds', 'bonus_amount' => 10, 'price' => 65000, 'type' => 'diamonds'],
            ['label' => '568 Diamonds', 'bonus_amount' => 25, 'price' => 141000, 'type' => 'diamonds'],
            ['label' => '853 Diamonds', 'bonus_amount' => 50, 'price' => 210000, 'type' => 'diamonds'],
        ];
        foreach ($diamondDenoms as $denom) {
            TopupDenomination::create([...$denom, 'topup_product_id' => $mlbbDiamonds->id]);
        }

        // MLBB Weekly Pass
        $mlbbPass = TopupProduct::where('name', 'MLBB Weekly Pass')->first();
        TopupDenomination::create(['topup_product_id' => $mlbbPass->id, 'label' => 'Weekly Pass', 'bonus_amount' => 0, 'price' => 30000, 'type' => 'weekly_pass']);
        TopupDenomination::create(['topup_product_id' => $mlbbPass->id, 'label' => 'Monthly Pass', 'bonus_amount' => 0, 'price' => 100000, 'type' => 'weekly_pass']);

        // Free Fire Diamonds
        $ffDiamonds = TopupProduct::where('name', 'FF Diamonds')->first();
        $ffDenoms = [
            ['label' => '100 Diamonds', 'bonus_amount' => 0, 'price' => 16000, 'type' => 'diamonds'],
            ['label' => '310 Diamonds', 'bonus_amount' => 10, 'price' => 47000, 'type' => 'diamonds'],
            ['label' => '520 Diamonds', 'bonus_amount' => 30, 'price' => 78000, 'type' => 'diamonds'],
            ['label' => '1060 Diamonds', 'bonus_amount' => 60, 'price' => 155000, 'type' => 'diamonds'],
        ];
        foreach ($ffDenoms as $denom) {
            TopupDenomination::create([...$denom, 'topup_product_id' => $ffDiamonds->id]);
        }

        // FF Membership
        $ffMembership = TopupProduct::where('name', 'FF Membership')->first();
        TopupDenomination::create(['topup_product_id' => $ffMembership->id, 'label' => 'Weekly Membership', 'bonus_amount' => 0, 'price' => 28000, 'type' => 'weekly_pass']);
        TopupDenomination::create(['topup_product_id' => $ffMembership->id, 'label' => 'Monthly Membership', 'bonus_amount' => 0, 'price' => 90000, 'type' => 'weekly_pass']);

        // Valorant Points
        $vpProduct = TopupProduct::where('name', 'Valorant Points')->first();
        $vpDenoms = [
            ['label' => '475 VP', 'bonus_amount' => 0, 'price' => 75000, 'type' => 'points'],
            ['label' => '1000 VP', 'bonus_amount' => 0, 'price' => 150000, 'type' => 'points'],
            ['label' => '2050 VP', 'bonus_amount' => 50, 'price' => 300000, 'type' => 'points'],
            ['label' => '5350 VP', 'bonus_amount' => 150, 'price' => 750000, 'type' => 'points'],
        ];
        foreach ($vpDenoms as $denom) {
            TopupDenomination::create([...$denom, 'topup_product_id' => $vpProduct->id]);
        }

        // Night Market Credits
        $nmProduct = TopupProduct::where('name', 'Night Market Credits')->first();
        TopupDenomination::create(['topup_product_id' => $nmProduct->id, 'label' => 'Night Market Bundle', 'bonus_amount' => 50, 'price' => 50000, 'type' => 'points']);

        // EA FC Points
        $fcPoints = TopupProduct::where('name', 'FC Points')->first();
        TopupDenomination::create(['topup_product_id' => $fcPoints->id, 'label' => '1050 FC Points', 'bonus_amount' => 0, 'price' => 160000, 'type' => 'points']);
        TopupDenomination::create(['topup_product_id' => $fcPoints->id, 'label' => '2200 FC Points', 'bonus_amount' => 200, 'price' => 320000, 'type' => 'points']);
    }
}