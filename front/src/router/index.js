import Vue from "vue"
import VueRouter from "vue-router"
import PageIndex from "@/pages/Index"
import PageAdd from "@/pages/Add"

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
]

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes,
})

export default router
