<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
    public function getWeather(string $city): array
{
    $cacheKey = "weather_v3_{$city}_".now()->format('YmdHi'); // Nouvelle clé plus robuste

    // Forcer la régénération si Ctrl+F5 est utilisé
    $forceRefresh = request()->hasHeader('Cache-Control') && 
                   str_contains(request()->header('Cache-Control'), 'no-cache');

    if ($forceRefresh) {
        Cache::store('redis')->forget($cacheKey);
    }

    return Cache::store('redis')->remember($cacheKey, now()->addMinutes(5), function() use ($city) {
        $response = Http::retry(2, 100)->timeout(3)->get(env('OPENWEATHERMAP_BASE_URL').'/weather', [
            'q' => $city,
            'appid' => env('OPENWEATHERMAP_API_KEY'),
            'units' => 'metric',
            'lang' => 'fr',
        ]);

        return array_merge($response->json(), [
            'cached_at' => now()->toDateTimeString(),
            'expires_at' => now()->addMinutes(5)->toDateTimeString(),
            'source' => 'live'
        ]);
    });
}
}