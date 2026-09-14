<?php

namespace App\Observers;

use App\Models\TicketBatch;
use App\Support\NotificationService;

class TicketBatchObserver
{
    public function created(TicketBatch $batch): void
    {
        if (app()->runningInConsole() && ! app()->runningUnitTests()) {
            return;
        }

        $match = $batch->match;

        $label = optional($match->teamA)->tag ?? 'Team A';
        $label .= ' vs ';
        $label .= optional($match->teamB)->tag ?? 'Team B';

        NotificationService::notifyAll(
            'ticket',
            'New Tickets Available',
            ($batch->venueZone->name ? strtoupper($batch->venueZone->name) : 'General Admission').' tickets for '.$label.' — '.$batch->seats_remaining.' seats left',
            $match ? route('tickets.show', $match) : route('tickets.index')
        );
    }
}