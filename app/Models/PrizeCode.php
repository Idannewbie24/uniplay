<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PrizeCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'order_type', 'order_id', 'code',
        'prize', 'status', 'claimed_at',
    ];

    protected function casts(): array
    {
        return [
            'claimed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generate(int $length = 8): string
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        do {
            $code = collect(range(1, $length))
                ->map(fn () => $alphabet[random_int(0, strlen($alphabet) - 1)])
                ->implode('');
        } while (static::where('code', $code)->exists());

        return $code;
    }

    public static function issue(?int $userId, string $orderType, int $orderId): self
    {
        return static::create([
            'user_id' => $userId,
            'order_type' => $orderType,
            'order_id' => $orderId,
            'code' => static::generate(8),
            'status' => 'pending',
        ]);
    }

    public function isMine(int $userId): bool
    {
        return (int) $this->user_id === $userId;
    }
}