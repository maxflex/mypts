import Vue from "vue"
import moment from "moment"

const filters = {
  formatPts(value) {
    return Number(value).toLocaleString()
  },

  dateTime(date) {
    return moment(date).format("dddd D MMMM в HH:mm")
  },

  time(value) {
    return value.substring(0, 5)
  },
}

for (let filterName in filters) {
  Vue.filter(filterName, filters[filterName])
}
