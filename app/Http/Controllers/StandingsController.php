<?php

namespace App\Http\Controllers;

use App\Models\Standing;
use App\Models\Tournament;
use Illuminate\Http\Request;

class StandingsController extends Controller
{
    public function index(Request $request)
    {
        $tournamentId = $request->input('tournament_id');

        $standingsQuery = Standing::with([
            'team:id,name,tag,logo',
            'tournament:id,name,stage,game_id',
        ])->orderBy('rank');

        if ($tournamentId) {
            $standingsQuery->where('tournament_id', $tournamentId);
        }

        $standings = $standingsQuery->get()->groupBy('tournament_id');

        $tournaments = Tournament::with('game:id,name,icon')
            ->where('status', '!=', 'upcoming')
            ->orderByDesc('status')
            ->orderBy('name')
            ->get();

        return view('standings.index', compact('standings', 'tournaments', 'tournamentId'));
    }
}
