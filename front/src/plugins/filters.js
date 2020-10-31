import Vue from "vue"

const filters = {
  formatPts(value) {
    return Number(value).toLocaleString()
  },

  time(value) {
    return value.substring(0, 5)
  },
}

for (let filterName in filters) {
  Vue.filter(filterName, filters[filterName])
}
