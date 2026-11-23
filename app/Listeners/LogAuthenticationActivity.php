<?php

namespace App\Listeners;

use App\Enums\ActivityAction;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

class LogAuthenticationActivity
{
    public function __construct(private ActivityLogger $logger) {}

    public function handleLogin(Login $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }

        $event->user->forceFill(['last_login_at' => now()])->saveQuietly();

        $this->logger->log(ActivityAction::Login, 'Login ke panel admin', $event->user, user: $event->user);
    }

    public function handleLogout(Logout $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }

        $this->logger->log(ActivityAction::Logout, 'Logout dari panel admin', $event->user, user: $event->user);
    }

    public function handleFailed(Failed $event): void
    {
        $email = (string) ($event->credentials['email'] ?? '');

        $this->logger->log(
            ActivityAction::LoginFailed,
            'Percobaan login gagal untuk '.$email,
            $event->user instanceof User ? $event->user : null,
            ['email' => $email],
        );
    }
}
