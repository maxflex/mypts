<template>
  <v-container fill-height>
    <v-row v-if="records === undefined">
      <v-col align="center">
        <Loader />
      </v-col>
    </v-row>
    <div class="records" v-else>
      <div>
        <div>
          <div class="justify-center flex-items">
            <div class="display-4 font-weight-bold mr-2 secondary--text">
              {{ records.max.pts | formatPts }}
            </div>
            <span class="subtitle-1 grey--text">
              pts
            </span>
          </div>
          <div class="mt-4 caption grey--text">
            {{ formatDate(records.max.updated_at) }}
          </div>
        </div>
      </div>
      <v-divider></v-divider>
      <div>
        <div class="justify-center flex-items">
          <div class="display-4 font-weight-bold mr-2 error--text">
            {{ records.min.pts | formatPts }}
          </div>
          <span class="subtitle-1 grey--text">
            pts
          </span>
        </div>
        <div class="mt-4 caption grey--text">
          {{ formatDate(records.min.updated_at) }}
        </div>
      </div>
    </div>
  </v-container>
</template>

<script>
const apiUrl = "records"
import Loader from "@/components/Loader"

export default {
  components: { Loader },

  data() {
    return {
      records: undefined,
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    loadData() {
      this.$http.get(apiUrl).then(r => (this.records = r.data))
    },

    formatDate(date) {
      return this.$moment(date).format("DD.MM.YY в HH:mm")
    },
  },
}
</script>

<style lang="scss">
.records {
  height: 70%;
  display: flex;
  flex-direction: column;
  justify-content: space-around;
  width: 100%;
  text-align: center;
}
</style>
