<?php

namespace App\Http\Middleware;

use Closure;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;

class MaintenanceModeEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $maintenanceModeEnabled = Setting::getBooleanValue('features.maintenance_mode', 'false');

        // Bypass for admins
        $user = auth()->user();

        // Get actual user if impersonating
        $impersonator = session()->get('impersonator_id')
            ? User::find(session('impersonator_id'))
            : $user;
        if (
            $maintenanceModeEnabled &&
            $impersonator &&
            $impersonator->can('byPassMaintenanceRole', User::class)
        ) {
            return $next($request);
        }

        if ($maintenanceModeEnabled && !$impersonator->can('byPassMaintenanceRole', User::class)) {
            // Render Inertia component and convert to Symfony response
            // return Inertia::render('auth/maintenance')
            //     ->toResponse($request);
        }

        return $next($request);
    }
}
