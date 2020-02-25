import Vue from "vue"
import Vuex from "vuex"
import auth from "./auth"
import menu from "./menu"

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    auth,
    menu,
  },
  state: {
    snackBar: {
      show: false,
      text: "",
      color: "red",
    },
    loading: false,
  },
  mutations: {
    message(state, { text, color = "success" }) {
      state.snackBar = {
        text,
        color,
        show: true,
      }
    },
    loading(state, loading) {
      state.loading = loading
    },
  },
  actions: {},
})
