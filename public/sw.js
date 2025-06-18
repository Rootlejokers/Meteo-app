const CACHE_VERSION = 'v2';
const CACHE_NAME = `jokers-weather-${CACHE_VERSION}`;
const OFFLINE_PAGE = '/offline.html';

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll([
        '/',
        '/weather',
        OFFLINE_PAGE,
        '/css/app.css',
        '/js/app.js'
      ]))
  );
});

self.addEventListener('fetch', (event) => {
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request)
                .catch(() => {
                    // Envoie un signal au frontend
                    return new Response(JSON.stringify({ offline: true }), {
                        headers: { 'Content-Type': 'application/json' }
                    });
                })
        );
    }
});