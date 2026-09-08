<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Short extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id', 'title', 'description', 'video_url', 'video_path', 'thumbnail',
        'duration_seconds', 'views_count', 'creator_name', 'category_tag',
    ];

    public function match(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class, 'match_id');
    }
}
