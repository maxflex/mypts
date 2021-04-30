<template>
  <div class="graph-page">
    <FullscreenLoader v-if="data === undefined" />
    <Graph :data="data" v-else />
  </div>
</template>

<script>
import { API_URL } from "@/components/History"
import Graph from "@/components/Graph/Graph"
import FullscreenLoader from "@/components/FullscreenLoader"

export default {
  components: { Graph, FullscreenLoader },

  data() {
    return {
      data: undefined,
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    loadData() {
      const params = { mode: "by-day" }
      this.$http.get(API_URL, { params }).then(r => {
        this.data = r.data.data
      })
    },
  },
}
</script>

<style lang="scss">
.graph-page {
  height: 100%;
  padding: 0 0 12px;
  & > div {
    width: 100%;
    height: 100%;
  }
}
</style>
