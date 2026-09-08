<?php

namespace App\Http\Controllers;

use App\Models\Short;
use Illuminate\Http\Request;

class ShortsController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->input('category');

        $query = Short::with('match:id,team_a_id,team_b_id,scheduled_at')
            ->orderByDesc('views_count');

        if ($category) {
            $query->where('category_tag', $category);
        }

        $shorts = $query->paginate(20);

        $categories = Short::distinct()
            ->whereNotNull('category_tag')
            ->pluck('category_tag')
            ->sort()
            ->values();

        return view('shorts.index', compact('shorts', 'categories', 'category'));
    }
}
