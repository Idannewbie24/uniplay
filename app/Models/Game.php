<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'publisher', 'icon', 'banner', 'category'];

    public function tournaments(): HasMany
    {
        return $this->hasMany(Tournament::class);
    }

    public function topupProducts(): HasMany
    {
        return $this->hasMany(TopupProduct::class);
    }
}
