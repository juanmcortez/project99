<?php

namespace App\Services\ActivityLogs;

use App\Enums\ActivityLogAction;
use App\Models\ActivityLogs\ActivityLog;

class ActivityLogService
{
    /**
     * @param  array<string, mixed>|null  $properties
     */
    public static function log(
        ActivityLogAction $action,
        ?string $description = null,
        ?array $properties = null,
        ?int $userId = null
    ): ActivityLog {
        return ActivityLog::create([
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'description' => $description,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
}
