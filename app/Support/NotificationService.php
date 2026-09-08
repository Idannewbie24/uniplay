<?php

namespace App\Support;

use App\Models\AppNotification;
use App\Models\User;

class NotificationService
{
    public static function notifyAll(string $type, string $title, string $message, ?string $link = null): void
    {
        $userIds = User::whereNotNull('email_verified_at')
            ->pluck('id');

        if ($userIds->isEmpty()) {
            return;
        }

        $expiresAt = now()->addDay();

        $rows = $userIds->map(fn ($id) => [
            'user_id'     => $id,
            'type'        => $type,
            'title'       => $title,
            'message'     => $message,
            'link'        => $link,
            'expires_at'  => $expiresAt,
            'created_at'  => now(),
            'updated_at'  => now(),
        ])->all();

        AppNotification::insert($rows);
    }
}
