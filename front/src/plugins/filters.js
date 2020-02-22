import Vue from "vue"

const filters = {
  formatPts(value) {
    return Number(value).toLocaleString()
  },
}

for (let filterName in filters) {
  Vue.filter(filterName, filters[filterName])
}
