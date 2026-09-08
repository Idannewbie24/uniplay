<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameMatch extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected static function newFactory()
    {
        return \Database\Factories\MatchFactory::new();
    }
    protected $fillable = [
        'tournament_id', 'team_a_id', 'team_b_id', 'venue_id',
        'scheduled_at', 'status', 'score_a', 'score_b',
        'stream_url', 'current_map', 'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'is_featured' => 'boolean',
        ];
    }

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function teamA(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_a_id');
    }

    public function teamB(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_b_id');
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function shorts(): HasMany
    {
        return $this->hasMany(Short::class, 'match_id');
    }

    public function ticketBatches(): HasMany
    {
        return $this->hasMany(TicketBatch::class, 'match_id');
    }
}
