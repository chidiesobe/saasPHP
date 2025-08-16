<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class AuthenticatedSessionController extends Controller
{
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }


    /**
     * Social authentication
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        return $this->socialLoginCallback('google');
    }

    public function redirectToMicrosoft(): RedirectResponse
    {
        return Socialite::driver('microsoft')->redirect();
    }

    public function handleMicrosoftCallback(): RedirectResponse
    {
        return $this->socialLoginCallback('microsoft');
    }

    public function redirectToYahoo(): RedirectResponse
    {
        return Socialite::driver('yahoo')->redirect();
    }

    public function handleYahooCallback(): RedirectResponse
    {
        return $this->socialLoginCallback('yahoo');
    }

    public function redirectToGithub(): RedirectResponse
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback(Request $request): RedirectResponse
    {
        return $this->socialLoginCallback('github');
    }

    public function redirectToTwitter(): RedirectResponse
    {
        return Socialite::driver('twitter')->redirect();
    }

    public function handleTwitterCallback(Request $request): RedirectResponse
    {
        return $this->socialLoginCallback('twitter');
    }


    private function socialLoginCallback($provider): RedirectResponse
    {
        $user = Socialite::driver($provider)->user();
        $social_user = User::firstOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'name' => $user->name,
                'password' => bcrypt(Str::random(16)),
                'email_verified_at' => now(),
            ]
        );

        // Do not change users password on login
        if (!$social_user->wasRecentlyCreated) {
            $social_user->update([
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($social_user, true);
        return redirect()->intended(route('dashboard', absolute: false));
    }
}
