<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TopupProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id', 'name', 'description', 'thumbnail', 'server_region',
        'fulfillment_method', 'support_hours', 'is_official_partner', 'rating',
    ];

    protected function casts(): array
    {
        return ['is_official_partner' => 'boolean', 'rating' => 'float'];
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function denominations(): HasMany
    {
        return $this->hasMany(TopupDenomination::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(TopupOrder::class);
    }
}
