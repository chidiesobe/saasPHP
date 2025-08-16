<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting;
use App\Models\MagicLink;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class MagicLinkController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        // Generate raw code and expiration
        $rawCode = Str::random(40);
        $hashedCode = Hash::make($rawCode);
        $expiresAt = now()->addMinutes(5);

        // Save hashed code
        MagicLink::create([
            'user_id'    => $user->id,
            'code'       => $hashedCode,
            'expires_at' => $expiresAt,
        ]);

        // Build login URL
        $url = route('magic.verify', ['code' => $rawCode, 'email' => $user->email]);

        // Send email
        Mail::raw("Click here to login: $url", function ($message) use ($user) {
            $message->to($user->email)
                ->subject(Setting::getValue('site.name') . ' Magic Login Link');
        });

        return back()->with('status', 'A login link has been sent to your email address.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'code'  => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        // Find latest unused and unexpired magic link
        $magicLink = MagicLink::where('user_id', $user->id)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$magicLink || !Hash::check($request->code, $magicLink->code)) {
            return redirect()->route('login')->withErrors(['code' => 'Invalid or expired magic link.']);
        }

        // Mark the magic link as used
        $magicLink->update(['used_at' => Carbon::now()]);

        // Log the user in
        Auth::login($user);

        // Verify unverified email
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->intended(route('dashboard'));
    }
}
