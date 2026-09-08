<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameMatch;
use App\Models\Team;
use App\Models\TicketBatch;
use App\Models\TopupProduct;
use App\Models\Tournament;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            if ($request->expectsJson()) {
                return response()->json(['results' => []]);
            }
            return back()->with('error', 'Search query must be at least 2 characters.');
        }

        $term = "%{$query}%";

        $games = Game::where('name', 'like', $term)
            ->limit(5)
            ->get(['id', 'name', 'slug', 'icon', 'category']);

        $tournaments = Tournament::with('game:id,name,icon')
            ->where('name', 'like', $term)
            ->limit(5)
            ->get(['id', 'name', 'stage', 'status', 'game_id']);

        $teams = Team::where('name', 'like', $term)
            ->orWhere('tag', 'like', $term)
            ->limit(5)
            ->get(['id', 'name', 'tag', 'logo']);

        $matches = GameMatch::with(['teamA:id,name,tag', 'teamB:id,name,tag', 'tournament:id,name'])
            ->whereHas('teamA', fn ($q) => $q->where('name', 'like', $term)->orWhere('tag', 'like', $term))
            ->orWhereHas('teamB', fn ($q) => $q->where('name', 'like', $term)->orWhere('tag', 'like', $term))
            ->orWhereHas('tournament', fn ($q) => $q->where('name', 'like', $term))
            ->limit(5)
            ->get();

        $topupProducts = TopupProduct::with('game:id,name,icon')
            ->where('name', 'like', $term)
            ->orWhereHas('game', fn ($q) => $q->where('name', 'like', $term))
            ->limit(5)
            ->get(['id', 'name', 'game_id']);

        if ($request->expectsJson()) {
            return response()->json([
                'results' => [
                    'games' => $games,
                    'tournaments' => $tournaments,
                    'teams' => $teams,
                    'matches' => $matches,
                    'topup_products' => $topupProducts,
                ],
            ]);
        }

        return view('search.results', compact('games', 'tournaments', 'teams', 'matches', 'topupProducts', 'query'));
    }
}
