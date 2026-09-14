<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id', 'venue_zone_id', 'price',
        'seats_total', 'seats_remaining', 'status_badge',
    ];

    protected function casts(): array
    {
        return ['price' => 'decimal:2'];
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class, 'match_id');
    }

    public function venueZone(): BelongsTo
    {
        return $this->belongsTo(VenueZone::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(TicketOrder::class);
    }

    public function getSoldPercentageAttribute(): int
    {
        if ($this->seats_total <= 0) return 0;
        return (int) round((($this->seats_total - $this->seats_remaining) / $this->seats_total) * 100);
    }
}
