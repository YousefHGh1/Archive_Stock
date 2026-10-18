const CACHE_NAME = "archive-stock-v4";
const urlsToCache = [
    "/",
    "/offline",
    "/manifest/manifest.json",
    "/icons/icon-72x72.png",
    "/icons/icon-96x96.png",
    "/icons/icon-128x128.png",
    "/icons/icon-144x144.png",
    "/icons/icon-152x152.png",
    "/icons/icon-192x192.png",
    "/icons/icon-384x384.png",
    "/icons/icon-512x512.png",
    "/assets/media/logos/favicon.png",
    "/assets/media/logos/logo-light.png",
    "/css/mobile-aside.css",
    "/js/mobile-aside.js",
    "/assets/js/jquery-3.3.1.min.js",
    "/assets/plugins/global/plugins.bundle.js",
    "/assets/js/scripts.bundle.js",
];

self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(urlsToCache).catch((error) => {
                console.log("Cache install failed:", error);
            });
        }),
    );
    self.skipWaiting();
});

self.addEventListener("fetch", (event) => {
    if (event.request.method !== "GET") {
        return;
    }

    // Skip caching for API calls, admin routes, and user-specific pages
    if (event.request.url.includes("/api/") ||
        event.request.url.includes("/admin") ||
        event.request.url.includes("/login") ||
        event.request.url.includes("/logout") ||
        event.request.url.includes("/dashboard")) {
        return;
    }

    event.respondWith(
        caches
            .match(event.request)
            .then((response) => {
                if (response) {
                    return response;
                }

                return fetch(event.request).then((response) => {
                    // Don't cache non-successful responses
                    if (!response || response.status !== 200 || response.type !== "basic") {
                        return response;
                    }

                    // Clone the response
                    const responseToCache = response.clone();

                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseToCache);
                    });

                    return response;
                });
            })
            .catch(() => {
                // Return a custom offline page if available
                return caches.match("/offline");
            }),
    );
});

self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (CACHE_NAME !== cacheName) {
                        console.log("Deleting old cache:", cacheName);
                        return caches.delete(cacheName);
                    }
                }),
            );
        }),
    );
    self.clients.claim();
});
