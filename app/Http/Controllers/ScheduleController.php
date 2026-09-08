<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameMatch;
use App\Models\Tournament;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $gameId = $request->input('game_id');
        $tournamentId = $request->input('tournament_id');

        $query = GameMatch::with([
            'tournament:id,name,stage,format,game_id',
            'teamA:id,name,tag,logo',
            'teamB:id,name,tag,logo',
            'venue:id,name,city',
        ])->orderBy('scheduled_at');

        if ($gameId) {
            $query->whereHas('tournament', function ($q) use ($gameId) {
                $q->where('game_id', $gameId);
            });
        }

        if ($tournamentId) {
            $query->where('tournament_id', $tournamentId);
        }

        $matches = $query->get()->groupBy(fn ($match) => $match->scheduled_at->format('Y-m-d'));

        $games = Game::orderBy('name')->get();

        $tournaments = Tournament::orderBy('name')->get();

        return view('schedule.index', compact('matches', 'games', 'tournaments', 'gameId', 'tournamentId'));
    }
}
