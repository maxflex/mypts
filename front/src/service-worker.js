workbox.setConfig({
  debug: true,
})

const bgSyncPlugin = new workbox.backgroundSync.Plugin("queue", {
  maxRetentionTime: 24 * 60, // 24 hours
})

workbox.precaching.precacheAndRoute(self.__precacheManifest)

workbox.routing.registerRoute(
  ({ url }) => url.pathname.match("/api/"),
  new workbox.strategies.NetworkOnly({
    plugins: [bgSyncPlugin],
  }),
  "PUT",
)

workbox.routing.registerRoute(
  ({ url }) => {
    if (url.search.indexOf("comment=") !== -1) {
      return false
    }
    return url.pathname.match("/api/")
  },
  new workbox.strategies.NetworkFirst({
    cacheName: "api",
    plugins: [
      new workbox.expiration.Plugin({
        maxEntries: 30,
      }),
    ],
  }),
)

workbox.routing.registerRoute(
  new RegExp("https://fonts.(?:googleapis|gstatic).com/(.*)"),
  new workbox.strategies.CacheFirst({
    cacheName: "googleapis",
  }),
)
