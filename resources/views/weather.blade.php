<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jokers Weather | Plateforme Météo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-6 shadow-lg">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-3xl font-bold">Jokers Weather</h1>
            <p class="mt-2 opacity-90">Plateforme et Outils de Développement</p>
            <p class="text-sm mt-4 opacity-75">
                Application météo avec API externe et cache intelligent<br>
                Par <span class="font-semibold">Kemgang Eloge & Nanga Clodiane</span> - Étudiants en L2A Option Génie Logiciel
            </p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="glass-card p-6 max-w-2xl mx-auto">
            <!-- Formulaire -->
            <form id="search-form" class="flex gap-2 mb-6">
                <input
                    type="text"
                    name="city"
                    placeholder="Paris, Tokyo..."
                    class="flex-1 px-4 py-3 rounded-lg bg-white/80 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
                <button
                    type="submit"
                    class="bg-blue-700 hover:bg-blue-800 px-6 py-3 rounded-lg text-white font-medium transition-all duration-300 transform hover:scale-105"
                >
                    🔍 Rechercher
                </button>
            </form>

            <!-- Résultats -->
            <div id="weather-result" class="min-h-64">
                @isset($weather)
                    @include('weather-partial', ['weather' => $weather])
                @else
                    <div class="text-center py-12">
                        <div class="inline-block p-4 bg-blue-100 rounded-full">
                            <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-xl font-semibold text-gray-700">Recherchez une ville</h3>
                        <p class="mt-2 text-gray-500">Entrez le nom d'une ville pour obtenir la météo</p>
                    </div>
                @endisset
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-12 py-6 bg-white/50 border-t border-gray-200">
        <div class="container mx-auto px-4 text-center text-gray-600">
            <div class="flex justify-center space-x-6 mb-4">
                <a href="#" class="hover:text-blue-600">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                </a>
                <!-- Ajoutez d'autres icônes si besoin -->
            </div>
            <p class="text-sm">
                &copy; {{ date('Y') }} IAI Cameroun - Tous droits réservés<br>
                Projet académique - L2A Génie Logiciel
            </p>
        </div>
    </footer>

    <!-- Scripts JS -->
    <script>
    // Votre code JavaScript existant avec améliorations
    document.getElementById('search-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const city = e.target.city.value;
        const weatherResult = document.getElementById('weather-result');
        
        // Animation de chargement
        weatherResult.innerHTML = `
            <div class="text-center py-8">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                <p class="mt-4 text-blue-600">Chargement des données...</p>
            </div>
        `;

        try {
            const response = await fetch(`/weather?city=${encodeURIComponent(city)}&_ajax=1`);
            
            if (!response.ok) throw new Error(await response.text());
            
            const data = await response.json();
            weatherResult.innerHTML = data.html || `
                <div class="bg-red-50 text-red-600 p-4 rounded-lg">
                    <p>⚠️ Aucune donnée disponible</p>
                </div>
            `;
            
        } catch (error) {
            weatherResult.innerHTML = `
                <div class="bg-red-50 text-red-600 p-4 rounded-lg">
                    <p>⚠️ Erreur de connexion</p>
                    <p class="text-sm mt-1">${error.message}</p>
                </div>
            `;
            console.error("Erreur:", error);
        }
    });

    // Gestion du mode hors ligne
    const offlineAlert = document.createElement('div');
    offlineAlert.id = 'offline-alert';
    offlineAlert.className = 'hidden fixed bottom-4 right-4 bg-yellow-100 border-l-4 border-yellow-500 p-4 z-50 rounded-lg shadow-lg';
    offlineAlert.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>Mode hors ligne - Données non actualisées</span>
        </div>
    `;
    document.body.appendChild(offlineAlert);

    function updateOnlineStatus() {
        if (!navigator.onLine) {
            offlineAlert.classList.remove('hidden');
        } else {
            offlineAlert.classList.add('hidden');
        }
    }

    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);
    updateOnlineStatus(); // Vérification initiale
    </script>
</body>
</html>