<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueFaq extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'answer', 'order_index'];

    protected $table = 'venue_faqs';
}
