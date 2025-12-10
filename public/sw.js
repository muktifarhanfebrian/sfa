// Service Worker Sederhana (Hanya agar lolos syarat PWA Installable)
self.addEventListener('install', (e) => {
  console.log('[Service Worker] Install');
});

self.addEventListener('fetch', (e) => {
  // Langsung teruskan request ke jaringan (Network Only)
  // Jangan mencoba cache apa-apa agar tidak error layar hitam
  e.respondWith(fetch(e.request));
});
