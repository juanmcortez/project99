<?php

namespace App\Listeners;

use App\Enums\ActivityLogAction;
use App\Services\ActivityLogs\ActivityLogService;
use Illuminate\Auth\Events\Logout;

class LogUserLogout
{
    public function handle(Logout $event): void
    {
        ActivityLogService::log(
            ActivityLogAction::UserLogout,
            'User logged out',
            ['user_id' => $event->user?->getKey()],
            $event->user?->getKey()
        );
    }
}
