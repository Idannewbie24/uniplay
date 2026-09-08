<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'tag', 'logo', 'seed_rank'];

    public function matchesAsTeamA(): HasMany
    {
        return $this->hasMany(GameMatch::class, 'team_a_id');
    }

    public function matchesAsTeamB(): HasMany
    {
        return $this->hasMany(GameMatch::class, 'team_b_id');
    }

    public function standings(): HasMany
    {
        return $this->hasMany(Standing::class);
    }
}
