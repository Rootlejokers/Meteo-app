<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
    public function getWeather(string $city): array
{
    try {
        $response = Http::retry(3, 500)->get(env('OPENWEATHERMAP_BASE_URL').'/weather', [
            'q' => $city,
            'appid' => env('OPENWEATHERMAP_API_KEY'),
            'units' => 'metric',
            'lang' => 'fr',
        ]);

        if ($response->failed()) {
            throw new \Exception(
                $response->json()['message'] ?? 'Erreur inconnue de l\'API',
                $response->status()
            );
        }

        return $response->json();

    } catch (\Exception $e) {
        return [
            'error' => true,
            'message' => "Impossible de récupérer la météo : " . $e->getMessage(),
            'code' => $e->getCode()
        ];
    }
}
}