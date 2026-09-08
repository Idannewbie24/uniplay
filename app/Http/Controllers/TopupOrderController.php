<?php

namespace App\Http\Controllers;

use App\Models\PrizeCode;
use App\Models\SiteSetting;
use App\Models\TopupDenomination;
use App\Models\TopupOrder;
use App\Models\TopupProduct;
use Illuminate\Http\Request;

class TopupOrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:topup_products,id',
            'denomination_id' => 'required|exists:topup_denominations,id',
            'user_id' => 'required|string|max:255',
            'zone_id' => 'nullable|string|max:255',
        ]);

        $denomination = TopupDenomination::findOrFail($validated['denomination_id']);
        $product = TopupProduct::with('game:id,name')->findOrFail($validated['product_id']);

        $subtotal = $denomination->price;
        $fee = 0;
        $discount = 0;
        $total = $subtotal + $fee - $discount;

        $order = TopupOrder::create([
            'user_id' => auth()->id(),
            'topup_product_id' => $validated['product_id'],
            'topup_denomination_id' => $validated['denomination_id'],
            'game_user_id' => $validated['user_id'],
            'game_zone_id' => $validated['zone_id'] ?? null,
            'payment_channel' => 'whatsapp',
            'subtotal' => $subtotal,
            'fee' => $fee,
            'discount' => $discount,
            'total' => $total,
            'status' => 'pending',
        ]);

        $prizeCode = PrizeCode::issue(auth()->id(), 'topup', $order->id);

        $whatsappMessage = $this->buildWhatsappMessage(
            $product,
            $denomination,
            $validated,
            $total,
            $order->id,
            $prizeCode->code
        );
        $order->update(['whatsapp_message_snapshot' => $whatsappMessage]);

        $whatsappNumber = SiteSetting::get('whatsapp_number', '6281318847041');
        $encodedMessage = urlencode($whatsappMessage);
        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$encodedMessage}";

        return redirect()->away($whatsappUrl);
    }

    private function buildWhatsappMessage(
        TopupProduct $product,
        TopupDenomination $denomination,
        array $data,
        float $total,
        int $orderId,
        ?string $prizeCode = null
    ): string {
        $lines = [
            "🎮 *UniPlay Top-Up Order*",
            "",
            "📦 Product: {$product->name} ({$product->game->name})",
            "💎 Denomination: {$denomination->label}",
            "💰 Total: Rp " . number_format($total, 0, ',', '.'),
            "",
            "👤 Game User ID: {$data['user_id']}",
        ];

        if (!empty($data['zone_id'])) {
            $lines[] = "🗺️ Zone/Server: {$data['zone_id']}";
        }

        $lines[] = "";
        $lines[] = "Order ID: #{$orderId}";
        $lines[] = "Silakan lakukan pembayaran.";

        if ($prizeCode) {
            $lines[] = "";
            $lines[] = "🎟️ *Kode Undian Anda: {$prizeCode}*";
            $lines[] = "Gunakan kode ini di menu *Prize Claim* setelah pembayaran selesai.";
        }

        return implode("\n", $lines);
    }
}
