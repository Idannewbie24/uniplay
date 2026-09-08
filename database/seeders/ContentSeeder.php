<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\GameMatch;
use App\Models\PaymentMethod;
use App\Models\Short;
use App\Models\VenueFaq;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            ['title' => 'VCT University Finals - Live Now!', 'image' => 'banners/vct-finals-banner.jpg', 'link_url' => '/matches', 'placement' => 'home_carousel', 'start_date' => now()->subWeek(), 'end_date' => now()->addWeek(), 'is_active' => true],
            ['title' => 'Top Up MLBB Diamonds - Bonus 10%', 'image' => 'banners/mlbb-topup-promo.jpg', 'link_url' => '/topup', 'placement' => 'home_carousel', 'start_date' => now(), 'end_date' => now()->addMonth(), 'is_active' => true],
            ['title' => 'MLBB Campus League S3 - Register Now', 'image' => 'banners/mlbb-campus-banner.jpg', 'link_url' => '/tournaments', 'placement' => 'home_carousel', 'start_date' => now(), 'end_date' => now()->addWeeks(2), 'is_active' => true],
            ['title' => 'CS2 Collegiate Cup - Coming Soon', 'image' => 'banners/cs2-cup-banner.jpg', 'link_url' => '/tournaments', 'placement' => 'sidebar', 'start_date' => now()->addWeek(), 'end_date' => now()->addMonth(), 'is_active' => true],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }

        $faqs = [
            ['question' => 'Bagaimana cara membeli tiket?', 'answer' => 'Pilih match yang ingin ditonton, pilih zona dan tier tiket, lalu lakukan pembayaran melalui metode yang tersedia. Tiket digital akan dikirim ke email Anda.', 'order_index' => 1],
            ['question' => 'Apakah tiket bisa dikembalikan?', 'answer' => 'Tiket hanya dapat dikembalikan maksimal 7 hari sebelum acara dimulai. Pengembalian dana akan diproses dalam 3-5 hari kerja.', 'order_index' => 2],
            ['question' => 'Bagaimana cara top up game?', 'answer' => 'Pilih game yang ingin di-top up, masukkan ID akun game, pilih nominal, lalu bayar. Top up akan otomatis masuk ke akun Anda dalam hitungan menit.', 'order_index' => 3],
            ['question' => 'Metode pembayaran apa yang diterima?', 'answer' => 'Kami menerima pembayaran melalui e-wallet (GoPay, OVO, Dana, ShopeePay), transfer bank (BCA, Mandiri, BRI, BNI), dan QRIS.', 'order_index' => 4],
            ['question' => 'Bagaimana jika top up gagal?', 'answer' => 'Jika top up gagal, dana Anda akan dikembalikan penuh dalam 1x24 jam. Hubungi admin melalui WhatsApp untuk bantuan lebih lanjut.', 'order_index' => 5],
            ['question' => 'Apakah ada diskon untuk pembelian tiket?', 'answer' => 'Ya, kami sering memberikan promo diskon khusus mahasiswa dan early bird. Ikuti media sosial kami untuk info promo terbaru.', 'order_index' => 6],
        ];

        foreach ($faqs as $faq) {
            VenueFaq::create($faq);
        }

        $paymentMethods = [
            ['name' => 'GoPay', 'logo' => 'payments/gopay-logo.png', 'type' => 'ewallet', 'is_active' => true],
            ['name' => 'OVO', 'logo' => 'payments/ovo-logo.png', 'type' => 'ewallet', 'is_active' => true],
            ['name' => 'Dana', 'logo' => 'payments/dana-logo.png', 'type' => 'ewallet', 'is_active' => true],
            ['name' => 'ShopeePay', 'logo' => 'payments/shopeepay-logo.png', 'type' => 'ewallet', 'is_active' => true],
            ['name' => 'QRIS', 'logo' => 'payments/qris-logo.png', 'type' => 'ewallet', 'is_active' => true],
            ['name' => 'BCA Virtual Account', 'logo' => 'payments/bca-logo.png', 'type' => 'bank', 'is_active' => true],
            ['name' => 'Mandiri Virtual Account', 'logo' => 'payments/mandiri-logo.png', 'type' => 'bank', 'is_active' => true],
            ['name' => 'BRI Virtual Account', 'logo' => 'payments/bri-logo.png', 'type' => 'bank', 'is_active' => true],
            ['name' => 'Transfer via WhatsApp', 'logo' => 'payments/wa-logo.png', 'type' => 'manual_wa', 'is_active' => true],
        ];

        foreach ($paymentMethods as $pm) {
            PaymentMethod::create($pm);
        }

        $finishedMatches = GameMatch::where('status', 'finished')->get();

        $shorts = [
            ['match_id' => $finishedMatches->first()?->id, 'title' => 'Insane Clutch 1v4 - Round 25', 'video_url' => 'https://youtube.com/shorts/abc123', 'thumbnail' => 'shorts/clutch-1v4.jpg', 'duration_seconds' => 45, 'views_count' => 85000, 'creator_name' => 'UniPlay Clips', 'category_tag' => 'clutch'],
            ['match_id' => $finishedMatches->first()?->id, 'title' => 'Team Wipe in 8 Seconds', 'video_url' => 'https://youtube.com/shorts/def456', 'thumbnail' => 'shorts/team-wipe.jpg', 'duration_seconds' => 32, 'views_count' => 120000, 'creator_name' => 'UniPlay Clips', 'category_tag' => 'highlight'],
            ['match_id' => $finishedMatches->get(1)?->id, 'title' => 'MVP Ace Round 30 - Grand Final', 'video_url' => 'https://youtube.com/shorts/ghi789', 'thumbnail' => 'shorts/ace-mvp.jpg', 'duration_seconds' => 58, 'views_count' => 210000, 'creator_name' => 'UniPlay Clips', 'category_tag' => 'ace'],
            ['match_id' => $finishedMatches->get(1)?->id, 'title' => 'Best Squad Wipe Tournament Highlights', 'video_url' => 'https://youtube.com/shorts/jkl012', 'thumbnail' => 'shorts/squad-wipe.jpg', 'duration_seconds' => 64, 'views_count' => 95000, 'creator_name' => 'Gaming Indo', 'category_tag' => 'mvp'],
            ['match_id' => $finishedMatches->first()?->id, 'title' => 'Comeback Round - 10K Damage Dealt', 'video_url' => 'https://youtube.com/shorts/mno345', 'thumbnail' => 'shorts/comeback.jpg', 'duration_seconds' => 72, 'views_count' => 67000, 'creator_name' => 'UniPlay Clips', 'category_tag' => 'topplay'],
            ['match_id' => $finishedMatches->get(2)?->id, 'title' => 'Top 5 Plays of the Week', 'video_url' => 'https://youtube.com/shorts/pqr678', 'thumbnail' => 'shorts/top5.jpg', 'duration_seconds' => 90, 'views_count' => 340000, 'creator_name' => 'UniPlay Official', 'category_tag' => 'highlight'],
        ];

        foreach ($shorts as $short) {
            Short::create($short);
        }
    }
}
