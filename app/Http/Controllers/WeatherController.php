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