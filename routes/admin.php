<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Games
    Route::get('games', [AdminController::class, 'gameIndex'])->name('games.index');
    Route::get('games/create', [AdminController::class, 'gameCreate'])->name('games.create');
    Route::post('games', [AdminController::class, 'gameStore'])->name('games.store');
    Route::get('games/{game}/edit', [AdminController::class, 'gameEdit'])->name('games.edit');
    Route::put('games/{game}', [AdminController::class, 'gameUpdate'])->name('games.update');
    Route::delete('games/{game}', [AdminController::class, 'gameDestroy'])->name('games.destroy');

    // Teams
    Route::get('teams', [AdminController::class, 'teamIndex'])->name('teams.index');
    Route::get('teams/create', [AdminController::class, 'teamCreate'])->name('teams.create');
    Route::post('teams', [AdminController::class, 'teamStore'])->name('teams.store');
    Route::get('teams/{team}/edit', [AdminController::class, 'teamEdit'])->name('teams.edit');
    Route::put('teams/{team}', [AdminController::class, 'teamUpdate'])->name('teams.update');
    Route::delete('teams/{team}', [AdminController::class, 'teamDestroy'])->name('teams.destroy');

    // Tournaments
    Route::get('tournaments', [AdminController::class, 'tournamentIndex'])->name('tournaments.index');
    Route::get('tournaments/create', [AdminController::class, 'tournamentCreate'])->name('tournaments.create');
    Route::post('tournaments', [AdminController::class, 'tournamentStore'])->name('tournaments.store');
    Route::get('tournaments/{tournament}/edit', [AdminController::class, 'tournamentEdit'])->name('tournaments.edit');
    Route::put('tournaments/{tournament}', [AdminController::class, 'tournamentUpdate'])->name('tournaments.update');
    Route::delete('tournaments/{tournament}', [AdminController::class, 'tournamentDestroy'])->name('tournaments.destroy');

    // Matches
    Route::get('matches', [AdminController::class, 'matchIndex'])->name('matches.index');
    Route::get('matches/create', [AdminController::class, 'matchCreate'])->name('matches.create');
    Route::post('matches', [AdminController::class, 'matchStore'])->name('matches.store');
    Route::get('matches/{match}/edit', [AdminController::class, 'matchEdit'])->name('matches.edit');
    Route::put('matches/{match}', [AdminController::class, 'matchUpdate'])->name('matches.update');
    Route::delete('matches/{match}', [AdminController::class, 'matchDestroy'])->name('matches.destroy');
    Route::patch('matches/{match}/score', [AdminController::class, 'matchScore'])->name('matches.score');

    // Venues
    Route::get('venues', [AdminController::class, 'venueIndex'])->name('venues.index');
    Route::get('venues/create', [AdminController::class, 'venueCreate'])->name('venues.create');
    Route::post('venues', [AdminController::class, 'venueStore'])->name('venues.store');
    Route::get('venues/{venue}/edit', [AdminController::class, 'venueEdit'])->name('venues.edit');
    Route::put('venues/{venue}', [AdminController::class, 'venueUpdate'])->name('venues.update');
    Route::delete('venues/{venue}', [AdminController::class, 'venueDestroy'])->name('venues.destroy');

    // Topup Products
    Route::get('topup-products', [AdminController::class, 'topupProductIndex'])->name('topup-products.index');
    Route::get('topup-products/create', [AdminController::class, 'topupProductCreate'])->name('topup-products.create');
    Route::post('topup-products', [AdminController::class, 'topupProductStore'])->name('topup-products.store');
    Route::get('topup-products/{topupProduct}/edit', [AdminController::class, 'topupProductEdit'])->name('topup-products.edit');
    Route::put('topup-products/{topupProduct}', [AdminController::class, 'topupProductUpdate'])->name('topup-products.update');
    Route::delete('topup-products/{topupProduct}', [AdminController::class, 'topupProductDestroy'])->name('topup-products.destroy');

    // Shorts
    Route::get('shorts', [AdminController::class, 'shortIndex'])->name('shorts.index');
    Route::get('shorts/create', [AdminController::class, 'shortCreate'])->name('shorts.create');
    Route::post('shorts', [AdminController::class, 'shortStore'])->name('shorts.store');
    Route::get('shorts/{short}/edit', [AdminController::class, 'shortEdit'])->name('shorts.edit');
    Route::put('shorts/{short}', [AdminController::class, 'shortUpdate'])->name('shorts.update');
    Route::delete('shorts/{short}', [AdminController::class, 'shortDestroy'])->name('shorts.destroy');

    // Banners
    Route::get('banners', [AdminController::class, 'bannerIndex'])->name('banners.index');
    Route::get('banners/create', [AdminController::class, 'bannerCreate'])->name('banners.create');
    Route::post('banners', [AdminController::class, 'bannerStore'])->name('banners.store');
    Route::get('banners/{banner}/edit', [AdminController::class, 'bannerEdit'])->name('banners.edit');
    Route::put('banners/{banner}', [AdminController::class, 'bannerUpdate'])->name('banners.update');
    Route::delete('banners/{banner}', [AdminController::class, 'bannerDestroy'])->name('banners.destroy');

    // FAQs
    Route::get('faqs', [AdminController::class, 'faqIndex'])->name('faqs.index');
    Route::get('faqs/create', [AdminController::class, 'faqCreate'])->name('faqs.create');
    Route::post('faqs', [AdminController::class, 'faqStore'])->name('faqs.store');
    Route::get('faqs/{venueFaq}/edit', [AdminController::class, 'faqEdit'])->name('faqs.edit');
    Route::put('faqs/{venueFaq}', [AdminController::class, 'faqUpdate'])->name('faqs.update');
    Route::delete('faqs/{venueFaq}', [AdminController::class, 'faqDestroy'])->name('faqs.destroy');

    // Orders
    Route::get('orders/topup', [AdminController::class, 'topupOrders'])->name('orders.topup');
    Route::get('orders/tickets', [AdminController::class, 'ticketOrders'])->name('orders.tickets');
    Route::patch('orders/topup/{order}/status', [AdminController::class, 'updateTopupStatus'])->name('orders.topup.status');
    Route::patch('orders/tickets/{order}/status', [AdminController::class, 'updateTicketStatus'])->name('orders.tickets.status');

    // Site Settings
    Route::get('settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('settings', [AdminController::class, 'settingsUpdate'])->name('settings.update');

    // Site Contents (Rulebooks + Legal docs)
    Route::get('contents', [AdminController::class, 'contentIndex'])->name('contents.index');
    Route::get('contents/create', [AdminController::class, 'contentCreate'])->name('contents.create');
    Route::post('contents', [AdminController::class, 'contentStore'])->name('contents.store');
    Route::get('contents/{siteContent}/edit', [AdminController::class, 'contentEdit'])->name('contents.edit');
    Route::put('contents/{siteContent}', [AdminController::class, 'contentUpdate'])->name('contents.update');
    Route::delete('contents/{siteContent}', [AdminController::class, 'contentDestroy'])->name('contents.destroy');

    // Venue Zones
    Route::get('venue-zones', [AdminController::class, 'venueZoneIndex'])->name('venue-zones.index');
    Route::get('venue-zones/create', [AdminController::class, 'venueZoneCreate'])->name('venue-zones.create');
    Route::post('venue-zones', [AdminController::class, 'venueZoneStore'])->name('venue-zones.store');
    Route::get('venue-zones/{venueZone}/edit', [AdminController::class, 'venueZoneEdit'])->name('venue-zones.edit');
    Route::put('venue-zones/{venueZone}', [AdminController::class, 'venueZoneUpdate'])->name('venue-zones.update');
    Route::delete('venue-zones/{venueZone}', [AdminController::class, 'venueZoneDestroy'])->name('venue-zones.destroy');

    // Ticket Batches
    Route::get('ticket-batches', [AdminController::class, 'ticketBatchIndex'])->name('ticket-batches.index');
    Route::get('ticket-batches/create', [AdminController::class, 'ticketBatchCreate'])->name('ticket-batches.create');
    Route::post('ticket-batches', [AdminController::class, 'ticketBatchStore'])->name('ticket-batches.store');
    Route::get('ticket-batches/{ticketBatch}/edit', [AdminController::class, 'ticketBatchEdit'])->name('ticket-batches.edit');
    Route::put('ticket-batches/{ticketBatch}', [AdminController::class, 'ticketBatchUpdate'])->name('ticket-batches.update');
    Route::delete('ticket-batches/{ticketBatch}', [AdminController::class, 'ticketBatchDestroy'])->name('ticket-batches.destroy');

    // Topup Denominations
    Route::get('denominations', [AdminController::class, 'denominationIndex'])->name('denominations.index');
    Route::get('denominations/create', [AdminController::class, 'denominationCreate'])->name('denominations.create');
    Route::post('denominations', [AdminController::class, 'denominationStore'])->name('denominations.store');
    Route::get('denominations/{denomination}/edit', [AdminController::class, 'denominationEdit'])->name('denominations.edit');
    Route::put('denominations/{denomination}', [AdminController::class, 'denominationUpdate'])->name('denominations.update');
    Route::delete('denominations/{denomination}', [AdminController::class, 'denominationDestroy'])->name('denominations.destroy');

    // Prize Codes + Gacha
    Route::get('prizes', [AdminController::class, 'prizeIndex'])->name('prizes.index');
    Route::get('prizes/gacha', [AdminController::class, 'gachaView'])->name('prizes.gacha');
    Route::post('prizes/gacha/run', [AdminController::class, 'gachaRun'])->name('prizes.gacha.run');
    Route::post('prizes/generate-code', [AdminController::class, 'generatePrizeCode'])->name('prizes.generate-code');
});
