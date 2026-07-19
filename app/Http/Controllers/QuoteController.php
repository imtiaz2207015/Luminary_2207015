<?php

namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;

/**
 * QuoteController
 *
 * Handles fetching a "reflection quote" from the API Ninjas Quotes API.
 * These quotes can be attached to a capsule or a post at creation time,
 * giving the user's future self a small piece of inspiration alongside
 * their memory.
 *
 * API Ninjas requires an API key, sent via the X-Api-Key header on every
 * request. The key is stored in .env as QUOTES_API_KEY and never exposed
 * to the frontend/JavaScript — it stays server-side inside this controller.
 *
 * @see https://api-ninjas.com/api/quotes
 */
class QuoteController extends Controller
{
    /**
     * The API Ninjas endpoint that returns a random quote.
     */
   private const QUOTES_API_URL = 'https://api.api-ninjas.com/v2/randomquotes';

    /**
     * Fetch a random inspirational quote and return it as JSON.
     *
     * Used by the AJAX "Get inspired" button on the capsule/post
     * creation forms. Returns only the fields the frontend needs
     * (quote text + author) rather than the raw API payload.
     */
    public function getRandomQuoteJson(): JsonResponse
    {
        $apiKey = env('QUOTES_API_KEY');

        if (!$apiKey) {
            return response()->json([
                'error' => 'Quote service is not configured. Missing API key.',
            ], 500);
        }

        try {
            $client = new Client(['timeout' => 5]);

            $response = $client->get(self::QUOTES_API_URL, [
                'headers' => [
                    'X-Api-Key' => $apiKey,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            // API Ninjas returns an array of quote objects: [{ quote, author, ... }]
            if (empty($data) || !isset($data[0]['quote'])) {
                return response()->json([
                    'error' => 'Could not fetch a quote right now. Please try again.',
                ], 502); // 502 Bad Gateway: upstream API gave us nothing usable
            }

            return response()->json([
                'quote_text' => $data[0]['quote'],
                'quote_author' => $data[0]['author'] ?? 'Unknown',
            ]);
        } catch (Exception $e) {
              \Log::error('Quote API failed: ' . $e->getMessage());
               return response()->json([
              'error' => 'Could not reach the quote service. Please try again shortly.',
           ], 500);
}
    }
}