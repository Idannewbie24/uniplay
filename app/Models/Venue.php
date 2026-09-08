<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'city', 'map_embed_url', 'capacity'];

    public function matches(): HasMany
    {
        return $this->hasMany(GameMatch::class, 'venue_id');
    }

    public function zones(): HasMany
    {
        return $this->hasMany(VenueZone::class);
    }
}
