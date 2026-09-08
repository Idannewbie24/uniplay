<?php

namespace App\Providers;

use App\Models\GameMatch;
use App\Models\TicketBatch;
use App\Observers\GameMatchObserver;
use App\Observers\TicketBatchObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        GameMatch::observe(GameMatchObserver::class);
        TicketBatch::observe(TicketBatchObserver::class);
    }
}
