import Vue from "vue"
import App from "./App.vue"
import router from "./router"
import store from "./store"
import vuetify from "@/plugins/vuetify"
require("@/assets/style.scss")
import axios from "axios"
require("@/plugins/filters")

const http = axios.create({
  baseURL: process.env.VUE_APP_API_URL,
})

if (sessionStorage.hasOwnProperty("api_token")) {
  http.defaults.headers.common = {
    Authorization: `Bearer ${sessionStorage.getItem("api_token")}`,
  }
}

Vue.config.productionTip = false
Vue.prototype.$http = http
Vue.prototype.$setLoader = function(loading) {
  this.$store.commit("loading", loading)
}

require("./plugins/moment")

new Vue({
  router,
  store,
  vuetify,
  render: h => h(App),
}).$mount("#app")
