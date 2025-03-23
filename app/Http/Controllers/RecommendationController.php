<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RecommendationController extends Controller
{
    public function getRecommendations($user_id)
    {
        // Use cache to store the recommendations for faster response
        $cacheKey = "recommendations_{$user_id}";

        // Check if recommendations exist in the cache
        $recommended_books = Cache::get($cacheKey);

        if (!$recommended_books) {
            // If not in cache, make an API request to the FastAPI service
            $response = Http::get("http://127.0.0.`1:8000/recommend/{$user_id}");

            if ($response->successful()) {
                // Get the recommended books from the response
                $recommended_books = $response->json()['recommended_books'];

                // Store the result in cache for 60 minutes
                Cache::put($cacheKey, $recommended_books, now()->addMinutes(60));
            } else {
                // Return an error response if FastAPI request fails
                return response()->json([
                    'error' => 'Could not fetch recommendations'
                ], 500);
            }
        }

        // Return the response with the user recommendations
        return response()->json([
            'user_id' => $user_id,
            'recommended_books' => $recommended_books
        ]);
    } 
}
