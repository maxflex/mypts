import Vue from "vue"
import App from "./App.vue"
import router from "./router"
import store from "./store"
import vuetify from "@/plugins/vuetify"
require("@/assets/style.scss")
import axios from "axios"
require("@/plugins/filters")
import Cookies from "js-cookie"
import VueTheMask from "vue-the-mask"

const http = axios.create({
  baseURL: process.env.VUE_APP_API_URL,
})

const apiToken = Cookies.get("api_token")
if (apiToken !== undefined) {
  http.defaults.headers.common = {
    Authorization: `Bearer ${apiToken}`,
  }
}

Vue.config.productionTip = false
Vue.prototype.$http = http
Vue.prototype.$setLoader = function(loading) {
  this.$store.commit("loading", loading)
}

Vue.use(VueTheMask)

require("./plugins/moment")

new Vue({
  router,
  store,
  vuetify,
  render: h => h(App),
}).$mount("#app")
