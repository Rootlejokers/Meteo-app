<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jokers Weather</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen">
    <div class="container mx-auto p-4 max-w-2xl">
        <!-- Carte principale -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Formulaire -->
            <div class="p-6 bg-blue-600 text-white">
                <form id="search-form" class="flex gap-2">
                    <input 
                        type="text" 
                        name="city" 
                        placeholder="Paris, Tokyo..." 
                        class="flex-1 px-4 py-2 rounded-lg text-gray-800 focus:outline-none"
                        required
                    >
                    <button 
                        type="submit" 
                        class="bg-blue-800 hover:bg-blue-900 px-6 py-2 rounded-lg transition"
                    >
                        🔍 Rechercher
                    </button>
                </form>
            </div>

            <!-- Résultats -->
            <div id="weather-result" class="p-6">
                @isset($weather)
                    @include('weather-partial', ['weather' => $weather])
                @else
                    <p class="text-center text-gray-500">Entrez une ville pour commencer</p>
                @endisset
            </div>
        </div>
    </div>

    <!-- Script AJAX -->
    <script>
        document.getElementById('search-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const city = e.target.city.value;
    const weatherResult = document.getElementById('weather-result');
    
    // Show loading state
    weatherResult.innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
            <p class="mt-4 text-blue-600">Chargement...</p>
        </div>
    `;

    try {
        const response = await fetch(`/weather?_ajax=1&city=${encodeURIComponent(city)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        
        if (!response.ok || !data.success) {
            throw new Error(data.html || 'Erreur inconnue');
        }

        weatherResult.innerHTML = data.html;
        history.pushState(null, null, `/weather?city=${encodeURIComponent(city)}`);
        
    } catch (error) {
        weatherResult.innerHTML = `
            <div class="bg-red-50 text-red-600 p-4 rounded-lg">
                <p>⚠️ Erreur lors de la recherche</p>
                <p class="text-sm mt-1">${error.message}</p>
            </div>
        `;
        console.error('Détails erreur:', error);
    }
});
    </script>
</body>
</html>