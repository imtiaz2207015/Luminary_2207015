<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * QuoteRateLimitMiddleware
 *
 * Limits how many times a single logged-in user can request a new
 * reflection quote in a short window. This mirrors the API-ninja.com's API
 * own free-tier limit (5 requests per 30 seconds) so that our app
 * never gets its shared IP throttled by hammering the "Get inspired"
 * button in the capsule/post creation form.
 *
 * The limit is tracked per authenticated user (not per IP), using
 * Laravel's cache, so two different users on the same network are
 * not penalised for each other's requests.
 */
class QuoteRateLimitMiddleware
{
    /**
     * Maximum number of quote requests allowed inside the time window.
     */
    private const MAX_ATTEMPTS = 5;

    /**
     * Length of the rate-limit window, in seconds.
     */
    private const DECAY_SECONDS = 30;

    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->user()?->id ?? $request->ip();
        $cacheKey = "quote-rate-limit:{$userId}";

        $attempts = Cache::get($cacheKey, 0);

        if ($attempts >= self::MAX_ATTEMPTS) {
            return response()->json([
                'error' => 'You are requesting quotes too quickly. Please wait a moment and try again.',
            ], 429); // 429 Too Many Requests
        }

        // Store the attempt count, expiring after the decay window so
        // the counter naturally resets.
        Cache::put($cacheKey, $attempts + 1, self::DECAY_SECONDS);

        return $next($request);
    }
}