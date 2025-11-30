self.addEventListener("install", event => {
    event.waitUntil(
        caches.open("mais-cache-v1").then(cache => {
            return cache.addAll([
                "/",               // halaman utama
                "/css/app.css",    // sesuaikan
                "/js/app.js"       // sesuaikan
            ]);
        })
    );
});

self.addEventListener("install", (event) => {
    console.log("Service Worker installed");
    self.skipWaiting();
});

self.addEventListener("activate", (event) => {
    console.log("Service Worker activated");
});

self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request).then(response => {
            return response || fetch(event.request);
        })
    );
});
