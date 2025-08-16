<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class MagicLinkTest extends TestCase
{
    use RefreshDatabase;


    public function test_it_can_render_magic_login_page()
    {
        $response = $this->get(route('magic.login'));

        $response->assertStatus(200);
        $response->assertInertia(fn($page) => $page->component('auth/magic-login'));
    }


    public function test_it_can_handle_magic_link_request_for_valid_user()
    {
        $user = User::factory()->create();

        $response = $this->post(route('magic.send'), [
            'email' => $user->login,
        ]);

        $response->assertRedirect(); // or change depending on response behavior

        // Optional: Assert something was stored (DB/cache/etc.)
        // e.g., assert session or cache key exists if your system tracks it
    }


    public function test_it_does_not_send_magic_link_for_unknown_email()
    {
        $response = $this->post(route('magic.send'), [
            'email' => 'notfound@example.com',
        ]);

        $response->assertRedirect(); // Or maybe 422, depending on validation

        // Could also assert session error or old input preserved
    }


public function test_it_logs_user_in_with_valid_magic_link()
{
    $user = User::factory()->create();

    $rawCode = Str::random(40);
    $hashedCode = Hash::make($rawCode);

    \App\Models\MagicLink::create([
        'user_id'    => $user->id,
        'code'       => $hashedCode,
        'expires_at' => now()->addMinutes(5),
    ]);

    $response = $this->get(route('magic.verify', [
        'email' => $user->email,
        'code'  => $rawCode,
    ]));

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($user);
}

public function test_it_rejects_invalid_or_expired_magic_link()
{
    $user = User::factory()->create();

    // Optionally: expired magic link
    \App\Models\MagicLink::create([
        'user_id'    => $user->id,
        'code'       => Hash::make('some-code'),
        'expires_at' => now()->subMinutes(1), // expired
    ]);

    $response = $this->get(route('magic.verify', [
        'email' => $user->email,
        'code'  => 'invalid-or-expired-code',
    ]));

    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors(['code']);
    $this->assertGuest();
}

}
