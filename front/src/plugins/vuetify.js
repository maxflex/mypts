import Vue from "vue"
import Vuetify from "vuetify"
import "vuetify/dist/vuetify.min.css"
import colors from "vuetify/lib/util/colors"
// Translation provided by Vuetify (typescript)
import ru from "vuetify/src/locale/ru.ts"

Vue.use(Vuetify)

const opts = {
  lang: {
    locales: { ru },
    current: "ru",
  },
  theme: {
    dark: false,
    themes: {
      dark: {
        primary: "#21CFF3",
        accent: "#FF4081",
        secondary: "#ffe18d",
        success: "#4CAF50",
        info: "#2196F3",
        warning: "#FB8C00",
        error: "#FF5252",
      },
      light: {
        primary: colors.grey.darken4,
        // accent: "#e91e63", // pink original
        accent: "#f3377a",
        // accent: "#ff5451", // orange
        secondary: "#b7e151",
        success: "#4CAF50",
        info: "#2196F3",
        warning: "#FB8C00",
        error: "#FF5252",
      },
    },
  },
}

export default new Vuetify(opts)
