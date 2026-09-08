<?php

namespace App\Http\Controllers;

use App\Models\GameMatch;
use App\Models\Tournament;
use App\Models\Venue;
use App\Models\VenueFaq;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = GameMatch::with([
            'tournament:id,name,stage,format',
            'teamA:id,name,tag,logo',
            'teamB:id,name,tag,logo',
            'venue:id,name,city',
            'ticketBatches' => function ($q) {
                $q->with('venueZone:id,name')
                    ->where('seats_remaining', '>', 0);
            },
        ])
            ->whereHas('ticketBatches', function ($q) {
                $q->where('seats_remaining', '>', 0);
            })
            ->where('status', 'upcoming')
            ->orderByDesc('created_at');

        $matches = $query->get();

        $faqs = VenueFaq::orderBy('order_index')->get();
        $venues = Venue::with('zones')->get();

        return view('tickets.index', compact('matches', 'faqs', 'venues'));
    }

    public function show(GameMatch $match)
    {
        $match->load([
            'tournament:id,name,stage,format,prize_pool',
            'teamA:id,name,tag,logo',
            'teamB:id,name,tag,logo',
            'venue:id,name,address,city,map_embed_url,capacity',
            'ticketBatches' => function ($q) {
                $q->with('venueZone:id,name,description')
                    ->where('seats_remaining', '>', 0)
                    ->orderBy('price');
            },
        ]);

        $faqs = VenueFaq::orderBy('order_index')->get();

        return view('tickets.show', compact('match', 'faqs'));
    }
}
