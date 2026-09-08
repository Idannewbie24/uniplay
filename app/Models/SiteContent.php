<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SiteContent extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'slug', 'title', 'body', 'order_index'];

    public function rulebooks(): HasMany
    {
        return $this->hasMany(self::class)->where('type', 'rule');
    }
}