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
  },
  mutations: {
    message(state, { text, color = "success" }) {
      state.snackBar = {
        text,
        color,
        show: true,
      }
    },
  },
  actions: {},
  modules: {},
})
