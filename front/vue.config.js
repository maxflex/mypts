module.exports = {
  // ...other vue-cli plugin options...
  pwa: {
    name: "My PTS",
    short_name: "My PTS",
    themeColor: "#f3377a",
    backgroundColor: "#ffffff",
    msTileColor: "#ffffff",
    display: "fullscreen",
    manifestOptions: {
      background_color: "#ffffff",
    },
    workboxPluginMode: "InjectManifest",
    workboxOptions: {
      // swSrc is required in InjectManifest mode.
      swSrc: "src/service-worker.js",
      // ...other Workbox options...
    },
  },
}
