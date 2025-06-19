<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
  public function getWeather(string $city): array
{
    $cacheKey = "weather_final_{$city}";
    $now = now()->setTimezone('UTC');

    return Cache::store('redis')->remember($cacheKey, now()->addMinutes(5), function() use ($city, $now) {
        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'q' => $city,
            'appid' => env('OPENWEATHERMAP_API_KEY'),
            'units' => 'metric',
            'lang' => 'fr',
        ]);

        $apiData = $response->json();
        
        // Ajoutez TOUTES les métadonnées de cache
        return array_merge($apiData, [
            'cache_metadata' => [
                'local_display_time' => $now->setTimezone('Africa/Douala')->format('H:i'),
                'cached_at_unix' => $now->timestamp,
                'expires_at_unix' => $now->timestamp + 300, // 5 minutes
                'timezone' => 'Africa/Douala'
            ]
        ]);
    });
}
}