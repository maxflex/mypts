import Vue from "vue"
import VueRouter from "vue-router"
import PageIndex from "@/pages/Index"
import PageAdd from "@/pages/Add"
import PageHistory from "@/pages/History"

Vue.use(VueRouter)

const routes = [
  {
    path: "/",
    name: "PageIndex",
    component: PageIndex,
  },
  {
    path: "/add",
    name: "PageAdd",
    component: PageAdd,
  },
  {
    path: "/history",
    name: "PageHistory",
    component: PageHistory,
  },
]

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes,
})

export default router
