<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Log an activity to the activity_logs table
     *
     * @param string $action Action performed (e.g., 'created_project', 'approved_project')
     * @param string|null $detail Additional details about the action
     * @param int|null $userId User ID (defaults to authenticated user)
     * @return ActivityLog|null
     */
    public static function log(string $action, ?string $detail = null, ?int $userId = null): ?ActivityLog
    {
        // Use authenticated user if no user_id provided
        $userId = $userId ?? Auth::id();

        // Don't log if no user is available
        if (!$userId) {
            return null;
        }

        return ActivityLog::create([
            'user_id' => $userId,
            'action' => $action,
            'detail' => $detail,
        ]);
    }
}
