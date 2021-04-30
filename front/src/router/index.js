import Vue from "vue"
import VueRouter from "vue-router"
import PageIndex from "@/pages/Index"
import PageHistory from "@/pages/History"
import PagePlan from "@/pages/Plan"
import PageRecords from "@/pages/Records"
import PageRules from "@/pages/Rules"
import PageGraph from "@/pages/Graph"
import PageAchievements from "@/pages/Achievements"

Vue.use(VueRouter)

const routes = [
  {
    path: "/",
    name: "PageIndex",
    component: PageIndex,
  },
  {
    path: "/history",
    name: "PageHistory",
    component: PageHistory,
  },
  {
    path: "/plan",
    name: "PagePlan",
    component: PagePlan,
  },
  {
    path: "/achievements",
    name: "PageAchievements",
    component: PageAchievements,
  },
  {
    path: "/records",
    name: "PageRecords",
    component: PageRecords,
  },
  {
    path: "/rules",
    name: "PageRules",
    component: PageRules,
  },
  {
    path: "/graph",
    name: "PageGraph",
    component: PageGraph,
  },
]

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes,
})

export default router
