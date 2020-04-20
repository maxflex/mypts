import Cookies from "js-cookie"
const apiTokenField = "api_token"

export default {
  signIn({ dispatch }, { docs, password }) {
    return this._vm.$http.post("auth/login", { docs, password }).then(r => {
      dispatch("setUser", r.data)
    })
  },

  async getLoggedUser({ dispatch }) {
    return this._vm.$http
      .get("profile")
      .then(r => {
        dispatch("setUser", r.data)
      })
      .catch(() => {
        dispatch("setUser", null)
      })
  },

  logout({ dispatch }) {
    // this._vm.$http.get("logout")
    Cookies.remove(apiTokenField)
    dispatch("setUser", null)
  },

  setUser({ commit }, user) {
    if (user !== null && apiTokenField in user) {
      this._vm.$http.defaults.headers.common = {
        Authorization: `Bearer ${user[apiTokenField]}`,
      }
      // save api token in storage
      Cookies.set(apiTokenField, user[apiTokenField], { expires: 60 })
    } else {
      this._vm.$http.defaults.headers.common = {}
    }
    commit("SET_USER", user)
  },
}
