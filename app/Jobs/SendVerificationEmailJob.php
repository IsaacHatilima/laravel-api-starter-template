<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendVerificationEmailJob implements ShouldQueue
{
    use Queueable;

    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(): void
    {
        $this->user->sendEmailVerificationNotification();
    }
}
