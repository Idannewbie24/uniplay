<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Game;
use App\Models\GameMatch;
use App\Models\MatchPrediction;
use App\Models\Short;
use App\Models\SiteSetting;

class HomeController extends Controller
{
    public function index()
    {
        $featuredMatch = GameMatch::with([
            'tournament:id,name,stage,format',
            'teamA:id,name,tag,logo',
            'teamB:id,name,tag,logo',
            'venue:id,name,city',
        ])
            ->whereIn('status', ['live', 'upcoming'])
            ->orderByRaw("CASE WHEN status = 'live' THEN 0 ELSE 1 END")
            ->orderBy('scheduled_at')
            ->first();

        $liveMatches = GameMatch::with([
            'tournament:id,name',
            'teamA:id,name,tag,logo',
            'teamB:id,name,tag,logo',
        ])
            ->where('status', 'live')
            ->orderBy('scheduled_at')
            ->limit(10)
            ->get();

        $games = Game::with(['topupProducts' => function ($q) {
            $q->withCount('denominations');
            $q->withSum('denominations', 'price');
        }])->orderBy('name')->get();

        $upcomingMatches = GameMatch::with([
            'tournament:id,name,stage',
            'teamA:id,name,tag,logo',
            'teamB:id,name,tag,logo',
            'venue:id,name,city',
            'ticketBatches' => function ($q) {
                $q->where('seats_remaining', '>', 0);
            },
        ])
            ->where('status', 'upcoming')
            ->whereHas('ticketBatches', function ($q) {
                $q->where('seats_remaining', '>', 0);
            })
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        $trendingShorts = Short::with('match:id,team_a_id,team_b_id')
            ->orderByDesc('views_count')
            ->limit(12)
            ->get();

        $shorts = $trendingShorts;

        $settings = SiteSetting::all()->pluck('value', 'key');

        if ($featuredMatch) {
            $predCounts = MatchPrediction::where('match_id', $featuredMatch->id)
                ->selectRaw('COUNT(*) as total')
                ->selectRaw('SUM(CASE WHEN team = "a" THEN 1 ELSE 0 END) as a')
                ->selectRaw('SUM(CASE WHEN team = "b" THEN 1 ELSE 0 END) as b')
                ->first();

            $predA = $predCounts && $predCounts->total ? (int) round(($predCounts->a / $predCounts->total) * 100) : null;
            $predB = $predCounts && $predCounts->total ? (int) round(($predCounts->b / $predCounts->total) * 100) : null;

            $myPrediction = auth()->check()
                ? MatchPrediction::where('match_id', $featuredMatch->id)
                    ->where('user_id', auth()->id())
                    ->first(['team'])
                : null;
        } else {
            $predA = null;
            $predB = null;
            $myPrediction = null;
        }

        return view('home.index', compact(
            'featuredMatch',
            'liveMatches',
            'games',
            'upcomingMatches',
            'shorts',
            'settings',
            'predA',
            'predB',
            'myPrediction',
        ));
    }
}