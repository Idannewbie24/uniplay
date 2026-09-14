<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TopupController;
use App\Http\Controllers\TopupOrderController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketOrderController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StandingsController;
use App\Http\Controllers\ShortsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MatchPredictionController;
use App\Http\Controllers\PrizeClaimController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::post('/prize/claim', [PrizeClaimController::class, 'claim'])->name('prize.claim');

Route::get('/topup', [TopupController::class, 'index'])->name('topup.index');
Route::get('/topup/{product}', [TopupController::class, 'show'])->name('topup.show');
Route::post('/topup/order', [TopupOrderController::class, 'store'])->name('topup.order');

Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
Route::get('/tickets/{match}', [TicketController::class, 'show'])->name('tickets.show');
Route::post('/tickets/order', [TicketOrderController::class, 'store'])->name('tickets.order');

Route::middleware('auth')->group(function () {
    Route::post('/predictions', [MatchPredictionController::class, 'vote'])->name('predictions.vote');
});

Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');
Route::get('/standings', [StandingsController::class, 'index'])->name('standings');
Route::get('/shorts', [ShortsController::class, 'index'])->name('shorts');

Route::get('/redeem', function () {
    return view('redeem');
})->name('redeem');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications', [NotificationController::class, 'clear'])->name('notifications.clear');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
