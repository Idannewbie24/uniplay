<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TopupOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'topup_product_id', 'topup_denomination_id',
        'game_user_id', 'game_zone_id', 'game_ign',
        'payment_channel', 'subtotal', 'fee', 'discount', 'total',
        'status', 'whatsapp_message_snapshot',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2', 'fee' => 'decimal:2',
            'discount' => 'decimal:2', 'total' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(TopupProduct::class, 'topup_product_id');
    }

    public function denomination(): BelongsTo
    {
        return $this->belongsTo(TopupDenomination::class, 'topup_denomination_id');
    }
}
