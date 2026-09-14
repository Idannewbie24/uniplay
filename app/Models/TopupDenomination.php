<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TopupDenomination extends Model
{
    use HasFactory;

    protected $fillable = [
        'topup_product_id', 'label', 'bonus_amount',
        'price', 'type',
    ];

    protected function casts(): array
    {
        return ['price' => 'decimal:2'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(TopupProduct::class, 'topup_product_id');
    }
}
