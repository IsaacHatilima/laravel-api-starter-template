<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyEmailWithPublicId extends VerifyEmail
{
    /**
     * @param  User  $notifiable
     */
    protected function verificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(
                config()->integer('auth.verification.expire', 60)
            ),
            [
                'id' => $notifiable->public_id,
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
