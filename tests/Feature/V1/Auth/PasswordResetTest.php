<?php

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

// uses(Tests\TestCase::class, Illuminate\Foundation\Testing\RefreshDatabase::class);

it('can request a password reset link', function () {
    Notification::fake();
    $user = User::factory()->create(['email' => 'test@example.com']);

    $response = $this->postJson('/api/v1/auth/forgot-password', [
        'email' => 'test@example.com',
    ]);

    $response->assertSuccessful();
    $response->assertJson(['message' => __(Password::RESET_LINK_SENT)]);

    Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) use ($user) {
        $token = $notification->token;
        $url = url(route('password.reset', [
            'token' => $token,
            'id' => $user->public_id,
        ], false));

        $reflection = new ReflectionClass($notification);
        $method = $reflection->getMethod('resetUrl');
        $method->setAccessible(true);
        $actualUrl = $method->invoke($notification, $user);

        return $actualUrl === $url;
    });
});

it('can reset password with a valid token', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('OldPassword123!'),
    ]);

    $token = Password::broker()->createToken($user);

    $response = $this->postJson('/api/v1/auth/set-password?id='.$user->public_id, [
        'token' => $token,
        'password' => 'NewPassword123!',
        'password_confirmation' => 'NewPassword123!',
    ]);

    $response->assertSuccessful();
    $response->assertJson(['message' => __('Password reset successful')]);

    $user->refresh();
    expect(Hash::check('NewPassword123!', $user->password))->toBeTrue();
});

it('cannot reset password without a token in database', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('OldPassword123!'),
    ]);

    $response = $this->postJson('/api/v1/auth/set-password?id='.$user->public_id, [
        'token' => 'some-token',
        'password' => 'NewPassword123!',
        'password_confirmation' => 'NewPassword123!',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['id']);
});
