<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Game;
use App\Models\GameMatch;
use App\Models\PrizeCode;
use App\Models\Short;
use App\Models\SiteContent;
use App\Models\SiteSetting;
use App\Models\Team;
use App\Models\TicketBatch;
use App\Models\TicketOrder;
use App\Models\TopupDenomination;
use App\Models\TopupOrder;
use App\Models\TopupProduct;
use App\Models\Tournament;
use App\Models\Venue;
use App\Models\VenueFaq;
use App\Models\VenueZone;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'games'       => Game::count(),
            'teams'       => Team::count(),
            'matches'     => GameMatch::count(),
            'topup_orders' => TopupOrder::count(),
            'ticket_orders' => TicketOrder::count(),
            'revenue'     => TopupOrder::where('status', 'delivered')->sum('total'),
        ];

        $recentTopupOrders = TopupOrder::with('user', 'product')
            ->latest()
            ->limit(10)
            ->get();

        $recentTicketOrders = TicketOrder::with('user', 'batch.match')
            ->latest()
            ->limit(10)
            ->get();

        $recentMatches = GameMatch::with('teamA', 'teamB', 'tournament')
            ->latest('scheduled_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentTopupOrders', 'recentTicketOrders', 'recentMatches'));
    }

    // ── Games ──────────────────────────────────────────────

    public function gameIndex()
    {
        $games = Game::withCount('tournaments', 'topupProducts')
            ->latest()
            ->paginate(20);

        return view('admin.games', compact('games'));
    }

    public function gameCreate()
    {
        return view('admin.game-form');
    }

    public function gameStore(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'slug'      => 'required|string|max:255|unique:games,slug',
            'publisher' => 'nullable|string|max:255',
            'category'  => 'nullable|string|max:255',
            'icon'      => 'nullable|image|max:2048',
            'banner'    => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon')->store('games', 'public');
        }
        if ($request->hasFile('banner')) {
            $validated['banner'] = $request->file('banner')->store('games', 'public');
        }

        Game::create($validated);

        return redirect()->route('admin.games.index')->with('success', 'Game created.');
    }

    public function gameEdit(Game $game)
    {
        return view('admin.game-form', ['game' => $game]);
    }

    public function gameUpdate(Request $request, Game $game)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'slug'      => 'required|string|max:255|unique:games,slug,' . $game->id,
            'publisher' => 'nullable|string|max:255',
            'category'  => 'nullable|string|max:255',
            'icon'      => 'nullable|image|max:2048',
            'banner'    => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon')->store('games', 'public');
        }
        if ($request->hasFile('banner')) {
            $validated['banner'] = $request->file('banner')->store('games', 'public');
        }

        $game->update($validated);

        return redirect()->route('admin.games.index')->with('success', 'Game updated.');
    }

    public function gameDestroy(Game $game)
    {
        foreach ($game->tournaments as $tournament) {
            foreach ($tournament->matches as $match) {
                $match->shorts()->delete();
                $match->ticketBatches()->delete();
            }
            $tournament->standings()->delete();
            $tournament->matches()->delete();
        }
        $game->tournaments()->delete();
        foreach ($game->topupProducts as $product) {
            $product->orders()->delete();
            $product->denominations()->delete();
        }
        $game->topupProducts()->delete();
        $game->delete();

        return redirect()->route('admin.games.index')->with('success', 'Game deleted.');
    }

    // ── Teams ──────────────────────────────────────────────

    public function teamIndex()
    {
        $teams = Team::withCount('matchesAsTeamA', 'matchesAsTeamB')
            ->latest()
            ->paginate(20);

        return view('admin.teams', compact('teams'));
    }

    public function teamCreate()
    {
        return view('admin.team-form');
    }

    public function teamStore(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'tag'       => 'required|string|max:10|unique:teams,tag',
            'logo'      => 'nullable|image|max:2048',
            'seed_rank' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('teams', 'public');
        }

        Team::create($validated);

        return redirect()->route('admin.teams.index')->with('success', 'Team created.');
    }

    public function teamEdit(Team $team)
    {
        return view('admin.team-form', ['team' => $team]);
    }

    public function teamUpdate(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'tag'       => 'required|string|max:10|unique:teams,tag,' . $team->id,
            'logo'      => 'nullable|image|max:2048',
            'seed_rank' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('teams', 'public');
        }

        $team->update($validated);

        return redirect()->route('admin.teams.index')->with('success', 'Team updated.');
    }

    public function teamDestroy(Team $team)
    {
        foreach ($team->matchesAsTeamB as $match) {
            $match->shorts()->delete();
            $match->ticketBatches()->delete();
        }
        foreach ($team->matchesAsTeamA as $match) {
            $match->shorts()->delete();
            $match->ticketBatches()->delete();
        }
        $team->matchesAsTeamA()->delete();
        $team->matchesAsTeamB()->delete();
        $team->standings()->delete();
        $team->delete();

        return redirect()->route('admin.teams.index')->with('success', 'Team deleted.');
    }

    // ── Tournaments ────────────────────────────────────────

    public function tournamentIndex()
    {
        $tournaments = Tournament::with('game')
            ->withCount('matches')
            ->latest()
            ->paginate(20);

        return view('admin.tournaments', compact('tournaments'));
    }

    public function tournamentCreate()
    {
        $games = Game::orderBy('name')->get();

        return view('admin.tournament-form', ['games' => $games]);
    }

    public function tournamentStore(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'game_id'    => 'required|exists:games,id',
            'stage'      => 'nullable|string|max:100',
            'format'     => 'nullable|string|max:100',
            'prize_pool' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'status'     => 'nullable|string|in:upcoming,ongoing,completed',
        ]);

        Tournament::create($validated);

        return redirect()->route('admin.tournaments.index')->with('success', 'Tournament created.');
    }

    public function tournamentEdit(Tournament $tournament)
    {
        $games = Game::orderBy('name')->get();

        return view('admin.tournament-form', ['tournament' => $tournament, 'games' => $games]);
    }

    public function tournamentUpdate(Request $request, Tournament $tournament)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'game_id'    => 'required|exists:games,id',
            'stage'      => 'nullable|string|max:100',
            'format'     => 'nullable|string|max:100',
            'prize_pool' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'status'     => 'nullable|string|in:upcoming,ongoing,completed',
        ]);

        $tournament->update($validated);

        return redirect()->route('admin.tournaments.index')->with('success', 'Tournament updated.');
    }

    public function tournamentDestroy(Tournament $tournament)
    {
        foreach ($tournament->matches as $match) {
            $match->shorts()->delete();
            $match->ticketBatches()->delete();
        }
        $tournament->standings()->delete();
        $tournament->matches()->delete();
        $tournament->delete();

        return redirect()->route('admin.tournaments.index')->with('success', 'Tournament deleted.');
    }

    // ── Matches ────────────────────────────────────────────

    public function matchIndex()
    {
        $matches = GameMatch::with('tournament', 'teamA', 'teamB', 'venue')
            ->latest('scheduled_at')
            ->paginate(20);

        return view('admin.matches', compact('matches'));
    }

    public function matchCreate()
    {
        $tournaments = Tournament::orderBy('name')->get();
        $teams       = Team::orderBy('name')->get();
        $venues      = Venue::orderBy('name')->get();

        return view('admin.match-form', compact('tournaments', 'teams', 'venues'));
    }

    public function matchStore(Request $request)
    {
        $validated = $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'team_a_id'     => 'required|exists:teams,id',
            'team_b_id'     => 'required|exists:teams,id|different:team_a_id',
            'venue_id'      => 'nullable|exists:venues,id',
            'scheduled_at'  => 'required|date',
            'status'        => 'nullable|in:upcoming,live,finished,cancelled',
            'score_a'       => 'nullable|integer|min:0',
            'score_b'       => 'nullable|integer|min:0',
            'stream_url'    => 'nullable|url|max:500',
            'current_map'   => 'nullable|string|max:100',
            'is_featured'   => 'nullable|boolean',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['status'] = $validated['status'] ?? 'upcoming';
        $validated['score_a'] = $validated['score_a'] ?? 0;
        $validated['score_b'] = $validated['score_b'] ?? 0;

        GameMatch::create($validated);

        return redirect()->route('admin.matches.index')->with('success', 'Match created.');
    }

    public function matchEdit(GameMatch $match)
    {
        $tournaments = Tournament::orderBy('name')->get();
        $teams       = Team::orderBy('name')->get();
        $venues      = Venue::orderBy('name')->get();

        return view('admin.match-form', ['match' => $match, 'tournaments' => $tournaments, 'teams' => $teams, 'venues' => $venues]);
    }

    public function matchUpdate(Request $request, GameMatch $match)
    {
        $validated = $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'team_a_id'     => 'required|exists:teams,id',
            'team_b_id'     => 'required|exists:teams,id|different:team_a_id',
            'venue_id'      => 'nullable|exists:venues,id',
            'scheduled_at'  => 'required|date',
            'status'        => 'nullable|in:upcoming,live,finished,cancelled',
            'score_a'       => 'nullable|integer|min:0',
            'score_b'       => 'nullable|integer|min:0',
            'stream_url'    => 'nullable|url|max:500',
            'current_map'   => 'nullable|string|max:100',
            'is_featured'   => 'nullable|boolean',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['score_a'] = $validated['score_a'] ?? 0;
        $validated['score_b'] = $validated['score_b'] ?? 0;

        $match->update($validated);

        return redirect()->route('admin.matches.index')->with('success', 'Match updated.');
    }

    public function matchDestroy(GameMatch $match)
    {
        $match->shorts()->delete();
        $match->ticketBatches()->delete();
        $match->delete();

        return redirect()->route('admin.matches.index')->with('success', 'Match deleted.');
    }

    public function matchScore(GameMatch $match, Request $request)
    {
        $validated = $request->validate([
            'score_a'     => 'required|integer|min:0',
            'score_b'     => 'required|integer|min:0',
            'current_map' => 'nullable|string|max:100',
            'status'      => 'nullable|in:live,finished',
        ]);

        $match->update($validated);

        return redirect()->back()->with('success', 'Score updated.');
    }

    // ── Venues ─────────────────────────────────────────────

    public function venueIndex()
    {
        $venues = Venue::withCount('matches', 'zones')
            ->latest()
            ->paginate(20);

        return view('admin.venues', compact('venues'));
    }

    public function venueCreate()
    {
        return view('admin.venue-form');
    }

    public function venueStore(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'address'        => 'nullable|string|max:500',
            'city'           => 'nullable|string|max:255',
            'map_embed_url'  => 'nullable|url|max:500',
            'capacity'       => 'nullable|integer|min:0',
        ]);

        Venue::create($validated);

        return redirect()->route('admin.venues.index')->with('success', 'Venue created.');
    }

    public function venueEdit(Venue $venue)
    {
        return view('admin.venue-form', ['venue' => $venue]);
    }

    public function venueUpdate(Request $request, Venue $venue)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'address'        => 'nullable|string|max:500',
            'city'           => 'nullable|string|max:255',
            'map_embed_url'  => 'nullable|url|max:500',
            'capacity'       => 'nullable|integer|min:0',
        ]);

        $venue->update($validated);

        return redirect()->route('admin.venues.index')->with('success', 'Venue updated.');
    }

    public function venueDestroy(Venue $venue)
    {
        $venue->delete();

        return redirect()->route('admin.venues.index')->with('success', 'Venue deleted.');
    }

    // ── Topup Products ─────────────────────────────────────

    public function topupProductIndex()
    {
        $products = TopupProduct::with('game')
            ->withCount('denominations', 'orders')
            ->latest()
            ->paginate(20);

        return view('admin.topup-products', compact('products'));
    }

    public function topupProductCreate()
    {
        $games = Game::orderBy('name')->get();

        return view('admin.topup-product-form', ['games' => $games]);
    }

    public function topupProductStore(Request $request)
    {
        $validated = $request->validate([
            'game_id'             => 'required|exists:games,id',
            'name'                => 'required|string|max:255',
            'description'         => 'nullable|string|max:1000',
            'thumbnail'           => 'nullable|image|max:2048',
            'server_region'       => 'nullable|string|max:100',
            'fulfillment_method'  => 'nullable|string|max:255',
            'support_hours'       => 'nullable|string|max:100',
            'is_official_partner' => 'nullable|boolean',
            'rating'              => 'nullable|numeric|min:0|max:5',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('topup-products', 'public');
        }

        $validated['is_official_partner'] = $request->boolean('is_official_partner');

        TopupProduct::create($validated);

        return redirect()->route('admin.topup-products.index')->with('success', 'Topup product created.');
    }

    public function topupProductEdit(TopupProduct $topupProduct)
    {
        $games = Game::orderBy('name')->get();

        return view('admin.topup-product-form', ['product' => $topupProduct, 'games' => $games]);
    }

    public function topupProductUpdate(Request $request, TopupProduct $topupProduct)
    {
        $validated = $request->validate([
            'game_id'             => 'required|exists:games,id',
            'name'                => 'required|string|max:255',
            'description'         => 'nullable|string|max:1000',
            'thumbnail'           => 'nullable|image|max:2048',
            'server_region'       => 'nullable|string|max:100',
            'fulfillment_method'  => 'nullable|string|max:255',
            'support_hours'       => 'nullable|string|max:100',
            'is_official_partner' => 'nullable|boolean',
            'rating'              => 'nullable|numeric|min:0|max:5',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('topup-products', 'public');
        }

        $validated['is_official_partner'] = $request->boolean('is_official_partner');

        $topupProduct->update($validated);

        return redirect()->route('admin.topup-products.index')->with('success', 'Topup product updated.');
    }

    public function topupProductDestroy(TopupProduct $topupProduct)
    {
        $topupProduct->orders()->delete();
        $topupProduct->denominations()->delete();
        $topupProduct->delete();

        return redirect()->route('admin.topup-products.index')->with('success', 'Topup product deleted.');
    }

    // ── Shorts ─────────────────────────────────────────────

    public function shortIndex()
    {
        $shorts = Short::with('match')
            ->latest()
            ->paginate(20);

        return view('admin.shorts', compact('shorts'));
    }

    public function shortCreate()
    {
        return view('admin.short-form');
    }

    public function shortStore(Request $request)
    {
        $validated = $request->validate([
            'match_id'         => 'nullable|exists:matches,id',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string|max:1000',
            'video_url'        => 'nullable|url|max:500',
            'video_path'       => 'nullable|file|mimes:mp4,webm,mov|max:51200',
            'thumbnail'        => 'nullable|image|max:2048',
            'duration_seconds' => 'nullable|integer|min:0',
            'views_count'      => 'nullable|integer|min:0',
            'creator_name'     => 'nullable|string|max:255',
            'category_tag'     => 'nullable|string|max:100',
        ]);

        if (empty($validated['video_url']) && ! $request->hasFile('video_path')) {
            return back()->withErrors(['video_url' => 'Provide either a video URL or upload a video file.'])->withInput();
        }

        if ($request->hasFile('video_path')) {
            $validated['video_path'] = $request->file('video_path')->store('shorts/videos', 'public');
        } else {
            unset($validated['video_path']);
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('shorts', 'public');
        }

        Short::create($validated);

        return redirect()->route('admin.shorts.index')->with('success', 'Short created.');
    }

    public function shortEdit(Short $short)
    {
        return view('admin.short-form', ['short' => $short]);
    }

    public function shortUpdate(Request $request, Short $short)
    {
        $validated = $request->validate([
            'match_id'         => 'nullable|exists:matches,id',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string|max:1000',
            'video_url'        => 'nullable|url|max:500',
            'video_path'       => 'nullable|file|mimes:mp4,webm,mov|max:51200',
            'thumbnail'        => 'nullable|image|max:2048',
            'duration_seconds' => 'nullable|integer|min:0',
            'views_count'      => 'nullable|integer|min:0',
            'creator_name'     => 'nullable|string|max:255',
            'category_tag'     => 'nullable|string|max:100',
        ]);

        if (empty($validated['video_url']) && ! $request->hasFile('video_path') && empty($short->video_path)) {
            return back()->withErrors(['video_url' => 'Provide a video URL, upload a video file, or keep the existing video.'])->withInput();
        }

        if ($request->hasFile('video_path')) {
            $validated['video_path'] = $request->file('video_path')->store('shorts/videos', 'public');
        } else {
            unset($validated['video_path']);
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('shorts', 'public');
        }

        $short->update($validated);

        return redirect()->route('admin.shorts.index')->with('success', 'Short updated.');
    }

    public function shortDestroy(Short $short)
    {
        $short->delete();

        return redirect()->route('admin.shorts.index')->with('success', 'Short deleted.');
    }

    // ── Banners ────────────────────────────────────────────

    public function bannerIndex()
    {
        $banners = Banner::latest()->paginate(20);

        return view('admin.banners', compact('banners'));
    }

    public function bannerCreate()
    {
        return view('admin.banner-form');
    }

    public function bannerStore(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'image'      => 'nullable|image|max:4096',
            'description'=> 'nullable|string|max:500',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'is_active'  => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('banners', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        Banner::create($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created.');
    }

    public function bannerEdit(Banner $banner)
    {
        return view('admin.banner-form', ['banner' => $banner]);
    }

    public function bannerUpdate(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'image'      => 'nullable|image|max:4096',
            'description'=> 'nullable|string|max:500',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'is_active'  => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('banners', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $banner->update($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated.');
    }

    public function bannerDestroy(Banner $banner)
    {
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted.');
    }

    // ── FAQs ───────────────────────────────────────────────

    public function faqIndex()
    {
        $faqs = VenueFaq::orderBy('order_index')->paginate(20);

        return view('admin.faqs', compact('faqs'));
    }

    public function faqCreate()
    {
        return view('admin.faq-form');
    }

    public function faqStore(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:1000',
            'answer'   => 'required|string|max:5000',
        ]);

        $validated['order_index'] = (VenueFaq::max('order_index') ?? 0) + 1;

        VenueFaq::create($validated);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created.');
    }

    public function faqEdit(VenueFaq $venueFaq)
    {
        return view('admin.faq-form', ['faq' => $venueFaq]);
    }

    public function faqUpdate(Request $request, VenueFaq $venueFaq)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:1000',
            'answer'   => 'required|string|max:5000',
        ]);

        $venueFaq->update($validated);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated.');
    }

    public function faqDestroy(VenueFaq $venueFaq)
    {
        $venueFaq->delete();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted.');
    }

    // ── Topup Orders ───────────────────────────────────────

    public function orders(Request $request)
    {
        $query = TopupOrder::with('user', 'product', 'denomination');
        $queryTicket = TicketOrder::with('user', 'batch.match');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
            $queryTicket->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('game_ign', 'like', "%{$search}%")
                  ->orWhere('game_user_id', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%"));
            });
            $queryTicket->where(function ($q) use ($search) {
                $q->where('buyer_name', 'like', "%{$search}%")
                  ->orWhere('buyer_phone', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $topupOrders = $query->latest()->paginate(15)->withQueryString();
        $ticketOrders = $queryTicket->latest()->paginate(15)->withQueryString();

        return view('admin.orders', compact('topupOrders', 'ticketOrders'));
    }

    public function topupOrders(Request $request) { return $this->orders($request); }
    public function ticketOrders(Request $request) { return $this->orders($request); }

    public function updateTopupStatus(TopupOrder $order, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,delivered,failed',
        ]);

        $order->update($validated);

        return redirect()->back()->with('success', 'Order status updated.');
    }

    public function updateTicketStatus(TicketOrder $order, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,checked_in,cancelled',
        ]);

        $order->update($validated);

        return redirect()->back()->with('success', 'Order status updated.');
    }

    // ── Site Settings ──────────────────────────────────────

    public function settings()
    {
        $settings = SiteSetting::pluck('value', 'key');

        return view('admin.settings', compact('settings'));
    }

    public function settingsUpdate(Request $request)
    {
        $validated = $request->validate([
            'enabled_qualities' => 'nullable|array',
            'enabled_qualities.*' => 'string',
            'stream_language' => 'nullable|string|max:100',
            'whatsapp_number' => 'nullable|string|max:30',
            'prize_rules' => 'nullable|string|max:2000',
        ]);

        $validated['enabled_qualities'] = implode(',', $request->input('enabled_qualities', []));

        foreach ($validated as $key => $value) {
            SiteSetting::set($key, $value);
        }

        return redirect()->route('admin.settings')->with('success', 'Settings saved.');
    }

    // ── Site Contents (Rulebooks + Legal) ──────────────────

    public function contentIndex()
    {
        $contents = SiteContent::orderBy('type')->orderBy('order_index')->paginate(20);

        return view('admin.contents', compact('contents'));
    }

    public function contentCreate()
    {
        return view('admin.content-form');
    }

    public function contentStore(Request $request)
    {
        $validated = $request->validate([
            'type'        => 'required|in:rule,legal',
            'title'       => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:site_contents,slug',
            'body'        => 'required|string|max:10000',
            'order_index' => 'nullable|integer|min:0',
        ]);

        $validated['order_index'] = $validated['order_index'] ?? 0;
        $validated['slug'] = ($validated['slug'] ?? null) ?: Str::slug($validated['title']);

        SiteContent::create($validated);

        return redirect()->route('admin.contents.index')->with('success', 'Content created.');
    }

    public function contentEdit(SiteContent $siteContent)
    {
        return view('admin.content-form', ['content' => $siteContent]);
    }

    public function contentUpdate(Request $request, SiteContent $siteContent)
    {
        $validated = $request->validate([
            'type'        => 'required|in:rule,legal',
            'title'       => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:site_contents,slug,' . $siteContent->id,
            'body'        => 'required|string|max:10000',
            'order_index' => 'nullable|integer|min:0',
        ]);

        $validated['order_index'] = $validated['order_index'] ?? 0;
        $siteContent->update($validated);

        return redirect()->route('admin.contents.index')->with('success', 'Content updated.');
    }

    public function contentDestroy(SiteContent $siteContent)
    {
        $siteContent->delete();

        return redirect()->route('admin.contents.index')->with('success', 'Content deleted.');
    }

    // ── Venue Zones ────────────────────────────────────────

    public function venueZoneIndex()
    {
        $zones = VenueZone::with('venue')->withCount('ticketBatches')->latest()->paginate(20);

        return view('admin.venue-zones', compact('zones'));
    }

    public function venueZoneCreate()
    {
        $venues = Venue::orderBy('name')->get();

        return view('admin.venue-zone-form', compact('venues'));
    }

    public function venueZoneStore(Request $request)
    {
        $validated = $request->validate([
            'venue_id'    => 'required|exists:venues,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        VenueZone::create($validated);

        return redirect()->route('admin.venue-zones.index')->with('success', 'Venue zone created.');
    }

    public function venueZoneEdit(VenueZone $venueZone)
    {
        $venues = Venue::orderBy('name')->get();

        return view('admin.venue-zone-form', compact('venueZone', 'venues'));
    }

    public function venueZoneUpdate(Request $request, VenueZone $venueZone)
    {
        $validated = $request->validate([
            'venue_id'    => 'required|exists:venues,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $venueZone->update($validated);

        return redirect()->route('admin.venue-zones.index')->with('success', 'Venue zone updated.');
    }

    public function venueZoneDestroy(VenueZone $venueZone)
    {
        $venueZone->delete();

        return redirect()->route('admin.venue-zones.index')->with('success', 'Venue zone deleted.');
    }

    // ── Ticket Batches ─────────────────────────────────────

    public function ticketBatchIndex()
    {
        $batches = TicketBatch::with(['match.tournament', 'match.teamA', 'match.teamB', 'venueZone'])
            ->latest()
            ->paginate(20);

        return view('admin.ticket-batches', compact('batches'));
    }

    public function ticketBatchCreate()
    {
        $matches = GameMatch::with('tournament', 'teamA', 'teamB')
            ->where('status', 'upcoming')
            ->orderByDesc('scheduled_at')
            ->get();
        $zones = VenueZone::with('venue')->orderBy('name')->get();

        return view('admin.ticket-batch-form', compact('matches', 'zones'));
    }

    public function ticketBatchStore(Request $request)
    {
        $validated = $request->validate([
            'match_id'       => 'required|exists:matches,id',
            'venue_zone_id'  => 'nullable|exists:venue_zones,id',
            'tier_name'      => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'seats_total'    => 'required|integer|min:1',
            'status_badge'   => 'nullable|in:available,limited_seats,selling_fast',
        ]);

        $validated['status_badge'] = $validated['status_badge'] ?? 'available';
        $validated['seats_remaining'] = $validated['seats_total'];

        TicketBatch::create($validated);

        return redirect()->route('admin.ticket-batches.index')->with('success', 'Ticket batch created.');
    }

    public function ticketBatchEdit(TicketBatch $ticketBatch)
    {
        $matches = GameMatch::with('tournament', 'teamA', 'teamB')
            ->where('status', 'upcoming')
            ->orderByDesc('scheduled_at')
            ->get();
        $zones = VenueZone::with('venue')->orderBy('name')->get();

        return view('admin.ticket-batch-form', compact('ticketBatch', 'matches', 'zones'));
    }

    public function ticketBatchUpdate(Request $request, TicketBatch $ticketBatch)
    {
        $validated = $request->validate([
            'match_id'       => 'required|exists:matches,id',
            'venue_zone_id'  => 'nullable|exists:venue_zones,id',
            'tier_name'      => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'seats_total'    => 'required|integer|min:1',
            'seats_remaining' => 'nullable|integer|min:0',
            'status_badge'   => 'nullable|in:available,limited_seats,selling_fast',
        ]);

        $validated['status_badge'] = $validated['status_badge'] ?? $ticketBatch->status_badge;

        if (! isset($validated['seats_remaining'])) {
            $validated['seats_remaining'] = $ticketBatch->seats_remaining;
        }

        $ticketBatch->update($validated);

        return redirect()->route('admin.ticket-batches.index')->with('success', 'Ticket batch updated.');
    }

    public function ticketBatchDestroy(TicketBatch $ticketBatch)
    {
        $ticketBatch->orders()->delete();
        $ticketBatch->delete();

        return redirect()->route('admin.ticket-batches.index')->with('success', 'Ticket batch deleted.');
    }

    // ── Topup Denominations ────────────────────────────────

    public function denominationIndex()
    {
        $denominations = TopupDenomination::with('product.game')
            ->latest()
            ->paginate(20);

        return view('admin.denominations', compact('denominations'));
    }

    public function denominationCreate()
    {
        $products = TopupProduct::with('game')->orderBy('name')->get();

        return view('admin.denomination-form', compact('products'));
    }

    public function denominationStore(Request $request)
    {
        $validated = $request->validate([
            'topup_product_id' => 'required|exists:topup_products,id',
            'label'            => 'required|string|max:255',
            'base_amount'      => 'nullable|integer|min:0',
            'bonus_amount'     => 'nullable|integer|min:0',
            'price'            => 'required|numeric|min:0',
            'badge'            => 'nullable|string|max:100',
            'type'             => 'nullable|string|max:100',
        ]);

        TopupDenomination::create($validated);

        return redirect()->route('admin.denominations.index')->with('success', 'Denomination created.');
    }

    public function denominationEdit(TopupDenomination $denomination)
    {
        $products = TopupProduct::with('game')->orderBy('name')->get();

        return view('admin.denomination-form', compact('denomination', 'products'));
    }

    public function denominationUpdate(Request $request, TopupDenomination $denomination)
    {
        $validated = $request->validate([
            'topup_product_id' => 'required|exists:topup_products,id',
            'label'            => 'required|string|max:255',
            'base_amount'      => 'nullable|integer|min:0',
            'bonus_amount'     => 'nullable|integer|min:0',
            'price'            => 'required|numeric|min:0',
            'badge'            => 'nullable|string|max:100',
            'type'             => 'nullable|string|max:100',
        ]);

        $denomination->update($validated);

        return redirect()->route('admin.denominations.index')->with('success', 'Denomination updated.');
    }

    public function denominationDestroy(TopupDenomination $denomination)
    {
        TopupOrder::where('topup_denomination_id', $denomination->id)->delete();
        $denomination->delete();

        return redirect()->route('admin.denominations.index')->with('success', 'Denomination deleted.');
    }

    // ── Prize Codes + Gacha ────────────────────────────────

    public function prizeIndex()
    {
        $codes = PrizeCode::with('user')->latest()->paginate(20);

        $stats = [
            'total'   => PrizeCode::count(),
            'pending' => PrizeCode::where('status', 'pending')->count(),
            'won'     => PrizeCode::where('status', 'won')->count(),
            'claimed' => PrizeCode::where('status', 'claimed')->count(),
        ];

        return view('admin.prizes', compact('codes', 'stats'));
    }

    public function gachaView()
    {
        $pending = PrizeCode::with('user')->where('status', 'pending')->count();
        $winners = PrizeCode::with('user')->where('status', 'won')->latest()->limit(20)->get();
        $previous = PrizeCode::with('user')->where('status', 'claimed')->latest()->limit(20)->get();

        return view('admin.gacha', compact('pending', 'winners', 'previous'));
    }

    public function generatePrizeCode()
    {
        $code = PrizeCode::generate(8);
        return response()->json(['code' => $code]);
    }

    public function gachaRun(Request $request)
    {
        $validated = $request->validate([
            'winners' => 'required|integer|min:1|max:50',
        ]);

        $count = (int) $validated['winners'];
        $pool = PrizeCode::with('user')->where('status', 'pending')
            ->inRandomOrder()
            ->limit(min($count, 50))
            ->get();

        if ($pool->isEmpty()) {
            return redirect()->route('admin.prizes.gacha')->withErrors(['winners' => 'There are no pending codes to draw from.']);
        }

        $prizes = [
            'Top Up Voucher Rp 50.000',
            'Top Up Voucher Rp 100.000',
            'Arena Merch Bundle',
            'Diamond Pack Special',
            'Premium Badge Eksklusif',
            'Grandstand Seat Upgrade',
        ];

        $winners = [];
        foreach ($pool as $code) {
            $code->update([
                'status' => 'won',
                'prize'  => $prizes[array_rand($prizes)],
            ]);
            $winners[] = $code->code . ($code->user ? ' (' . $code->user->name . ')' : '');
        }

        return redirect()->route('admin.prizes.gacha')
            ->with('gacha', implode(', ', $winners))
            ->with('success', 'Gacha draw complete: ' . count($winners) . ' winner(s) selected.');
    }
}
