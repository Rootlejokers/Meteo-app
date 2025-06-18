<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WeatherController extends Controller
{
    public function show(Request $request, WeatherService $weatherService)
{
    $city = $request->input('city');
     try {
        $weather = $city ? $weatherService->getWeather($city) : null;
    } catch (\Exception $e) {
        // Mode dégradé
        $weather = Cache::store('redis')->get("weather_{$city}_".date('Y-m-d_H'));
        
        if (!$weather && $request->wantsJson()) {
            return response()->json([
                'error' => 'offline',
                'message' => 'Mode hors ligne activé'
            ]);
        }
    }

    // Force JSON response for AJAX requests
    if ($request->ajax() || $request->has('_ajax')) {
        try {
            $weather = $city ? $weatherService->getWeather($city) : null;
            return response()->json([
                'html' => view('weather-partial', compact('weather'))->render(),
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'html' => '<div class="bg-red-50 text-red-600 p-4 rounded-lg">'
                        . '⚠️ Erreur serveur: ' . $e->getMessage() . '</div>',
                'success' => false
            ], 500);
        }
    }

    return view('weather', [
        'weather' => $city ? $weatherService->getWeather($city) : null
    ]);
}
}