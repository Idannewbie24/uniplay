<?php

namespace App\Http\Controllers;

use App\Models\GameMatch;
use App\Models\MatchPrediction;
use Illuminate\Http\Request;

class MatchPredictionController extends Controller
{
    public function vote(Request $request)
    {
        abort_unless(auth()->check(), 403, 'Please sign in to vote.');

        $validated = $request->validate([
            'match_id' => 'required|exists:matches,id',
            'team' => 'required|in:a,b',
        ]);

        abort_unless(in_array(GameMatch::find($validated['match_id'])?->status, ['upcoming', 'live']), 403);

        MatchPrediction::updateOrCreate(
            ['match_id' => $validated['match_id'], 'user_id' => auth()->id()],
            ['team' => $validated['team']]
        );

        $counts = MatchPrediction::where('match_id', $validated['match_id'])
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN team = "a" THEN 1 ELSE 0 END) as a')
            ->selectRaw('SUM(CASE WHEN team = "b" THEN 1 ELSE 0 END) as b')
            ->first();

        $total = (int) ($counts->total ?? 0);
        $a = (int) ($counts->a ?? 0);
        $b = (int) ($counts->b ?? 0);

        return response()->json([
            'ok' => true,
            'team' => $validated['team'],
            'percentage_a' => $total ? (int) round(($a / $total) * 100) : 50,
            'percentage_b' => $total ? (int) round(($b / $total) * 100) : 50,
            'total' => $total,
        ]);
    }
}