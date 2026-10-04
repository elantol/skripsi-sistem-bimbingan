<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    /**
     * Send in-app notification to a user.
     */
    public function send(int $userId, string $title, string $message, string $type = 'info'): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'read_at' => null,
        ]);
    }
}
