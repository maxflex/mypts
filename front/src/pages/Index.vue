<template>
  <v-row>
    <v-col align="center">
      <v-progress-circular
        v-if="pts === undefined"
        color="accent"
        indeterminate
        size="64"
      ></v-progress-circular>
      <div v-else>
        <div class="justify-center flex-items">
          <div class="display-4 font-weight-bold mr-2 accent--text">
            {{ pts.total | formatPts }}
          </div>
          <span class="subtitle-1 grey--text">
            pts
          </span>
        </div>
        <div class="mt-10 justify-center flex-items">
          <Pts class="mr-1" :value="pts.today" />
          <span class="grey--text">сегодня</span>
        </div>
        <div
          class="mt-10 caption justify-center flex-items"
          style="opacity: .5"
        >
          <Pts class="mr-1" :value="pts.today" />
          <span class="grey--text">вчера</span>
        </div>
      </div>
    </v-col>
  </v-row>
</template>

<script>
const apiUrl = "pts"
import Pts from "@/components/Pts"

export default {
  components: { Pts },

  data() {
    return {
      pts: undefined,
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    loadData() {
      this.$http.get(apiUrl).then(r => (this.pts = r.data))
    },
  },
}
</script>
