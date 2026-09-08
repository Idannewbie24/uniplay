<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TicketOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'ticket_batch_id', 'quantity', 'buyer_name',
        'buyer_phone', 'total_price', 'qr_code_token', 'status',
    ];

    protected function casts(): array
    {
        return ['total_price' => 'decimal:2'];
    }

    protected static function booted(): void
    {
        static::creating(function (TicketOrder $order) {
            if (empty($order->qr_code_token)) {
                $order->qr_code_token = Str::random(64);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(TicketBatch::class, 'ticket_batch_id');
    }
}
