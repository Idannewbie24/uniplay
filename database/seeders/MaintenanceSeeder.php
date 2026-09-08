<?php

namespace Database\Seeders;

use App\Models\GameMatch;
use App\Models\MatchPrediction;
use App\Models\PrizeCode;
use App\Models\Short;
use App\Models\SiteContent;
use App\Models\SiteSetting;
use App\Models\TicketBatch;
use App\Models\Tournament;
use Illuminate\Database\Seeder;

class MaintenanceSeeder extends Seeder
{
    public function run(): void
    {
        // Remove demo/sample content — admin adds everything manually from now on.
        PrizeCode::query()->delete();
        MatchPrediction::query()->delete();
        Short::query()->delete();
        TicketBatch::query()->delete();
        GameMatch::query()->delete();
        Tournament::query()->delete();

        // ── Site settings ────────────────────────────────────────────────
        $settings = [
            'stream_quality'   => '1080p 60fps',
            'stream_language'  => 'English',
            'stream_viewers'   => '',
            'whatsapp_number'  => '6281318847041',
            'prize_rules'      => 'Enter the unique code you received after any Top-Up or Ticket purchase. If your code is drawn as a winner, the prize will be shown here instantly and claimed to your account.',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::set($key, $value);
        }

        // ── Rulebooks (editable by admin) ────────────────────────────────
        $rules = [
            [
                'title' => 'Player Conduct',
                'body'  => "1. All participants are expected to maintain respectful behaviour towards opponents, officials, and spectators.\n2. Abusive language, hate speech, threats, or harassment of any kind is strictly prohibited.\n3. Unsportsmanlike behaviour may result in a warning, a penalty, or disqualification from the tournament.",
            ],
            [
                'title' => 'Match Fairness & Anti-Cheat',
                'body'  => "1. Any form of cheating — including but not limited to scripting, aim assistance, or exploiting game bugs — is forbidden.\n2. Accounts found using third-party software will be immediately disqualified and reported.\n3. All matches are subject to random checks and replay reviews by the tournament committee.",
            ],
            [
                'title' => 'Schedule & Attendance',
                'body'  => "1. Teams must be ready to play no later than 10 minutes before the scheduled match time.\n2. A no-show of more than 15 minutes results in a forfeit.\n3. Reschedules are only allowed if both teams and the committee agree at least 24 hours in advance.",
            ],
            [
                'title' => 'Ticket & Attendance Policy',
                'body'  => "1. A valid e-ticket or matching QR code is required for entry.\n2. Tickets are non-transferable once scanned at the gate.\n3. Arrive at least 30 minutes early; late entry may result in reduced viewing areas.",
            ],
        ];

        foreach ($rules as $i => $rule) {
            SiteContent::updateOrCreate(
                ['slug' => 'rule-' . ($i + 1)],
                [
                    'type' => 'rule',
                    'title' => $rule['title'],
                    'body' => $rule['body'],
                    'order_index' => $i + 1,
                ]
            );
        }

        // ── Legal documents (editable by admin) ──────────────────────────
        $legal = [
            [
                'slug'  => 'privacy-policy',
                'title' => 'Privacy Policy',
                'body'  => "Your privacy matters to us. This policy explains what data we collect, why we collect it, and how you can control it.\n\n1. We collect account information (name, email) and order details (top-up and ticket purchases) to provide our services.\n2. Payment data is processed securely; we never store raw card numbers.\n3. We do not sell your personal data to third parties.\n4. You may request access, correction, or deletion of your data at any time by contacting our support team.",
            ],
            [
                'slug'  => 'terms-of-service',
                'title' => 'Terms of Service',
                'body'  => "By using UniPlay you agree to the following terms:\n\n1. You must be at least 13 years old to create an account.\n2. All top-up and ticket purchases are final once fulfilled.\n3. Misuse of the platform, fraud, or abuse of the prize claim system will result in a permanent ban.\n4. We reserve the right to update these terms; continued use of the platform constitutes acceptance of the updated terms.",
            ],
            [
                'slug'  => 'anti-cheat-policy',
                'title' => 'Anti-Cheat Policy',
                'body'  => "Fair play is the foundation of competitive esports.\n\n1. The use of any third-party tool that provides an unfair advantage is strictly forbidden.\n2. Violations are detected through automated systems and manual review.\n3. Penalties range from a match forfeit to a lifetime tournament ban.\n4. All decisions by the anti-cheat committee are final.",
            ],
        ];

        foreach ($legal as $doc) {
            SiteContent::updateOrCreate(
                ['slug' => $doc['slug']],
                [
                    'type' => 'legal',
                    'title' => $doc['title'],
                    'body' => $doc['body'],
                    'order_index' => 0,
                ]
            );
        }
    }
}