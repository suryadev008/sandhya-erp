<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSystemLicense
{
    // April 30, 2026 ke baad lock ho jayega agar payment nahi aayi
    public const LOCK_DATE = '2026-04-30';

    public function handle(Request $request, Closure $next): Response
    {
        // Lock screen routes ko bypass karo
        if ($request->routeIs('system.locked') || $request->routeIs('system.unlock')) {
            return $next($request);
        }

        if ($this->isSystemLocked()) {
            // AJAX requests ke liye JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'System locked. Please contact support.',
                    'locked' => true,
                ], 403);
            }

            return redirect()->route('system.locked');
        }

        return $next($request);
    }

    private function isSystemLocked(): bool
    {
        // Agar payment aa gayi to unlock hai (.env PAYMENT_RECEIVED=true)
        if (config('license.payment_received', false)) {
            return false;
        }

        // Secret key se session unlock kiya ho to bypass karo
        if (session('system_unlocked', false)) {
            return false;
        }

        // April 30, 2026 ke baad lock
        return now()->gt(\Carbon\Carbon::parse(self::LOCK_DATE)->endOfDay());
    }
}
