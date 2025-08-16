<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsVerified
{
    /**
     * Handle an incoming request.
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    
    public function handle(Request $request, Closure $next)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $emailVerified = $user->hasVerifiedEmail();
        $phoneVerified = $user->hasVerifiedPhone();

        // If both are verified, proceed
        if ($emailVerified && $phoneVerified) {
            return $next($request);
        }

        // If user has both email and phone, but either is not verified
        if ($user->email && $user->phone && (!$emailVerified || !$phoneVerified)) {
            if (!$emailVerified) {
                $user->sendVerificationEmailWithRateLimit();
            }
            if (!$phoneVerified) {
                $user->sendPhoneVerificationCodeWithRateLimit();
            }
            return Inertia::render('auth/verify-phone');
        }

        // If only phone exists and not verified
        if (!$phoneVerified && $user->phone) {
            $user->sendPhoneVerificationCodeWithRateLimit();
            return Inertia::render('auth/verify-phone');
        }

        // If only email exists and not verified
        if (!$emailVerified && $user->email) {
                $user->sendVerificationEmailWithRateLimit();
            return Inertia::render('auth/verify-email');
        }

        return $next($request);
    }
}
