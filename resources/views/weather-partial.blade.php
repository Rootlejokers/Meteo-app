@if(isset($weather['main']))
<div class="space-y-4">
    <!-- En-tête -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold">{{ $weather['name'] }}, {{ $weather['sys']['country'] }}</h2>
        <span class="text-sm text-gray-500">
    @php
        try {
            // Solution 1: Utiliser le nom de timezone si disponible
            $timezone = $weather['timezone'] ?? 'UTC';
            
            // Si c'est un nombre (offset en secondes), le convertir
            if (is_numeric($timezone)) {
                $timezone = timezone_name_from_abbr('', $timezone, false);
                if ($timezone === false) {
                    $timezone = 'UTC';
                }
            }
            
            echo now()->setTimezone($timezone)->format('H:i, D d M');
        } catch (Exception $e) {
            // Fallback en cas d'erreur
            echo now()->format('H:i, D d M');
        }
    @endphp
</span>
    </div>

    <!-- Données principales -->
    <div class="flex items-center gap-6">
        <div class="text-5xl font-light">{{ round($weather['main']['temp']) }}°C</div>
        <div>
            <img 
                src="https://openweathermap.org/img/wn/{{ $weather['weather'][0]['icon'] }}@2x.png" 
                alt="{{ $weather['weather'][0]['description'] }}"
                class="w-16 h-16"
            >
            <p class="capitalize">{{ $weather['weather'][0]['description'] }}</p>
        </div>
    </div>

    <!-- Détails -->
    <div class="grid grid-cols-3 gap-4 text-sm">
        <div class="bg-blue-50 p-3 rounded-lg">
            <p>Humidité</p>
            <p class="font-bold">{{ $weather['main']['humidity'] }}%</p>
        </div>
        <div class="bg-blue-50 p-3 rounded-lg">
            <p>Vent</p>
            <p class="font-bold">{{ round($weather['wind']['speed'] * 3.6) }} km/h</p>
        </div>
        <div class="bg-blue-50 p-3 rounded-lg">
            <p>Pression</p>
            <p class="font-bold">{{ $weather['main']['pressure'] }} hPa</p>
        </div>
    </div>
</div>
@else
<div class="bg-yellow-50 text-yellow-700 p-4 rounded-lg">
    <p>⚠️ Données météo incomplètes</p>
</div>
@endif