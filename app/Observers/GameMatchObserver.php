<?php

namespace App\Observers;

use App\Models\GameMatch;
use App\Support\NotificationService;

class GameMatchObserver
{
    public function created(GameMatch $match): void
    {
        if (app()->runningInConsole() && ! app()->runningUnitTests()) {
            return;
        }

        if (in_array($match->status, ['upcoming', 'live'])) {
            $this->scheduleNotification($match);
        }

        if ($match->status === 'finished') {
            $this->resultNotification($match);
        }
    }

    public function saving(GameMatch $match): void
    {
        if (app()->runningInConsole() && ! app()->runningUnitTests()) {
            return;
        }

        if ($match->exists && array_key_exists('status', $match->getDirty())) {
            $oldStatus = $match->getOriginal('status');
            $newStatus = $match->status;
            $wentFinished = $oldStatus !== 'finished' && $newStatus === 'finished';
            $isNewSchedule = $newStatus === 'upcoming' && $oldStatus !== 'upcoming';

            if ($wentFinished) {
                $this->resultNotification($match);
            } elseif ($isNewSchedule) {
                $this->scheduleNotification($match);
            }
        }
    }

    protected function scheduleNotification(GameMatch $match): void
    {
        $label = $match->teamA->tag ?? 'Team A';
        $label .= ' vs ';
        $label .= $match->teamB->tag ?? 'Team B';

        NotificationService::notifyAll(
            'schedule',
            'New Match Scheduled',
            $label.' — starts '.optional($match->scheduled_at)->format('M j, H:i'),
            route('schedule')
        );
    }

    protected function resultNotification(GameMatch $match): void
    {
        $label = $match->teamA->tag ?? 'Team A';
        $label .= ' vs ';
        $label .= $match->teamB->tag ?? 'Team B';

        NotificationService::notifyAll(
            'result',
            'Match Result Updated',
            $label.' • '.$match->score_a.' - '.$match->score_b,
            route('standings')
        );
    }
}