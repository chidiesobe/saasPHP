<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class TwoFactorController extends Controller
{
    /**
     * Show the user's password settings page.
     */
    public function edit(Request $request): Response
    {
        $two_factor_confirmed =  $request->user()->two_factor_confirmed_at ? true : false;
        return Inertia::render('settings/two-factor-authentication', [
            'twoFactorSecret' => $request->user()->two_factor_secret,
            'twoFactorQRCode' => $request->user()->two_factor_secret ? $request->user()->twoFactorQrCodeSvg() : '',
            'twoFactorRecoveryCodes' => $request->user()->two_factor_secret ? $request->user()->recoveryCodes() : '',
            'twoFactorConfirmation' =>  $two_factor_confirmed,
        ]);
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back();
    }
}
