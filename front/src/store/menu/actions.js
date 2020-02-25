export default {
  getUnfinishedPlansCount({ commit }) {
    const params = {
      count: 1,
      date: this._vm.$moment().format("YYYY-MM-DD"),
      is_finished: false,
    }
    this._vm.$http.get("plans", { params }).then(r => {
      commit("setUnfinishedPlansCount", r.data)
    })
  },
}
