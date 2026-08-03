// Service Worker for CharyMeld Adverts PWA
// Compatible with Chrome, Firefox, Safari, Edge, Samsung Internet
'use strict';

const CACHE_NAME = 'charymeld-adverts-v2';
const OFFLINE_URL = '/offline.html';

// Assets to cache immediately
const PRECACHE_ASSETS = [
    '/',
    '/manifest.json'
];

// Install event - cache essential assets
self.addEventListener('install', (event) => {
    console.log('[ServiceWorker] Install');
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('[ServiceWorker] Pre-caching assets');
                // Cache each asset individually to handle failures gracefully
                return Promise.allSettled(
                    PRECACHE_ASSETS.map(url =>
                        cache.add(url).catch(err => {
                            console.warn('[ServiceWorker] Failed to cache:', url, err);
                            return Promise.resolve(); // Don't fail the entire install
                        })
                    )
                );
            })
            .then(() => self.skipWaiting())
            .catch(err => {
                console.error('[ServiceWorker] Install failed:', err);
                // Still skip waiting to activate the service worker
                self.skipWaiting();
            })
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
    console.log('[ServiceWorker] Activate');
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('[ServiceWorker] Removing old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    return self.clients.claim();
});

// Fetch event - serve from cache, fallback to network
self.addEventListener('fetch', (event) => {
    // Skip cross-origin requests
    if (!event.request.url.startsWith(self.location.origin)) {
        return;
    }

    // Skip non-GET requests
    if (event.request.method !== 'GET') {
        return;
    }

    // Skip API, admin, and authenticated routes from service worker caching
    const url = new URL(event.request.url);
    const skipPaths = ['/api/', '/admin/', '/advertiser/', '/publisher/', '/notifications/', '/feed'];
    if (skipPaths.some(path => url.pathname.startsWith(path))) {
        // Let these requests bypass service worker and go directly to network
        return;
    }

    event.respondWith(
        caches.match(event.request)
            .then((cachedResponse) => {
                if (cachedResponse) {
                    // Return cached version and update cache in background
                    fetchAndCache(event.request);
                    return cachedResponse;
                }

                // Not in cache, fetch from network with redirect: 'follow'
                // Use standard fetch options for maximum compatibility
                return fetch(event.request, {
                    redirect: 'follow',
                    credentials: 'same-origin'
                })
                    .then(function(response) {
                        // Check if valid response (allow redirects)
                        if (!response || (response.status !== 200 && response.type !== 'opaqueredirect')) {
                            return response;
                        }

                        // Don't cache redirects
                        if (response.redirected || response.type === 'opaqueredirect') {
                            return response;
                        }

                        // Clone response for caching
                        const responseToCache = response.clone();

                        caches.open(CACHE_NAME)
                            .then((cache) => {
                                // Only cache GET requests
                                if (event.request.method === 'GET') {
                                    cache.put(event.request, responseToCache);
                                }
                            });

                        return response;
                    })
                    .catch(() => {
                        // Network failed, return offline page for navigation requests
                        if (event.request.mode === 'navigate') {
                            return caches.match(OFFLINE_URL);
                        }

                        // For other requests, return a generic offline response
                        return new Response('Offline', {
                            status: 503,
                            statusText: 'Service Unavailable',
                            headers: new Headers({
                                'Content-Type': 'text/plain'
                            })
                        });
                    });
            })
    );
});

// Helper function to fetch and update cache
function fetchAndCache(request) {
    return fetch(request)
        .then((response) => {
            if (!response || response.status !== 200 || response.type !== 'basic') {
                return response;
            }

            const responseToCache = response.clone();
            caches.open(CACHE_NAME)
                .then((cache) => {
                    cache.put(request, responseToCache);
                });

            return response;
        })
        .catch(() => {
            // Silently fail background updates
        });
}

// Push notification event
self.addEventListener('push', (event) => {
    console.log('[ServiceWorker] Push notification received');

    const data = event.data ? event.data.json() : {};
    const title = data.title || 'CharyMeld Adverts';
    const options = {
        body: data.body || 'You have a new notification',
        icon: '/images/icons/icon-192x192.png',
        badge: '/images/icons/icon-72x72.png',
        image: data.image,
        data: {
            url: data.url || '/'
        },
        actions: data.actions || [],
        vibrate: [200, 100, 200],
        tag: data.tag || 'charymeld-notification',
        requireInteraction: false
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

// Notification click event
self.addEventListener('notificationclick', (event) => {
    console.log('[ServiceWorker] Notification click received');

    event.notification.close();

    // Handle action buttons
    if (event.action) {
        console.log('[ServiceWorker] Notification action:', event.action);
    }

    // Open the URL from notification data
    const urlToOpen = event.notification.data?.url || '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true })
            .then((clientList) => {
                // Check if there's already a window open
                for (const client of clientList) {
                    if (client.url === urlToOpen && 'focus' in client) {
                        return client.focus();
                    }
                }
                // Open a new window
                if (clients.openWindow) {
                    return clients.openWindow(urlToOpen);
                }
            })
    );
});

// Background sync event (for future use)
self.addEventListener('sync', (event) => {
    console.log('[ServiceWorker] Background sync:', event.tag);

    if (event.tag === 'sync-data') {
        event.waitUntil(
            // Implement background sync logic here
            Promise.resolve()
        );
    }
});

// Message event - handle messages from clients
self.addEventListener('message', (event) => {
    console.log('[ServiceWorker] Message received:', event.data);

    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }

    if (event.data && event.data.type === 'CACHE_URLS') {
        event.waitUntil(
            caches.open(CACHE_NAME)
                .then((cache) => cache.addAll(event.data.urls))
        );
    }
});
