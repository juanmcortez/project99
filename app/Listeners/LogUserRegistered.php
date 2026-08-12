<?php

namespace App\Listeners;

use App\Enums\ActivityLogAction;
use App\Services\ActivityLogs\ActivityLogService;
use Illuminate\Auth\Events\Registered;

class LogUserRegistered
{
    public function handle(Registered $event): void
    {
        ActivityLogService::log(
            ActivityLogAction::UserRegistered,
            'User registered',
            ['user_id' => $event->user->getKey()],
            $event->user->getKey()
        );
    }
}
