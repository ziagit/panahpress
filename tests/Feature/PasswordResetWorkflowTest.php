<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_a_password_reset_link(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'role' => 'author',
            'email' => 'author@example.com',
        ]);

        $this->post(route('password.email', ['locale' => 'en']), [
            'email' => $user->email,
        ])->assertRedirect();

        Notification::assertSentTo(
            $user,
            ResetPasswordNotification::class
        );
    }

    public function test_user_can_reset_their_password_with_a_valid_token(): void
    {
        $user = User::factory()->create([
            'role' => 'author',
            'email' => 'author@example.com',
            'password' => Hash::make('old-password'),
        ]);

        $token = Password::broker()->createToken($user);

        $this->post(route('password.update', ['locale' => 'en']), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertRedirect(route('admin.posts.index', ['locale' => 'en']));

        $user->refresh();

        $this->assertTrue(Hash::check('new-password', $user->password));
    }
}
