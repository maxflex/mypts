import Vue from "vue"
import Vuex from "vuex"

Vue.use(Vuex)

export default new Vuex.Store({
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
  modules: {},
})
