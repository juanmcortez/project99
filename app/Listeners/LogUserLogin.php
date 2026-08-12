<?php

namespace App\Listeners;

use App\Enums\ActivityLogAction;
use App\Services\ActivityLogs\ActivityLogService;
use Illuminate\Auth\Events\Login;

class LogUserLogin
{
    public function handle(Login $event): void
    {
        ActivityLogService::log(
            ActivityLogAction::UserLogin,
            'User logged in',
            ['user_id' => $event->user->getKey()],
            $event->user->getKey()
        );
    }
}
