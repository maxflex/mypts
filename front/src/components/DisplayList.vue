<template>
  <div :class="{ invisible: items === undefined }">
    <div v-if="items !== undefined">
      <slot name="items" :items="items" v-if="items.length > 0"></slot>
      <NoData :no-elevation="noElevation" v-else />
    </div>
    <InfiniteLoading
      spinner="waveDots"
      @infinite="infiniteHandler"
      ref="InfiniteLoading"
    >
      <div slot="no-more"></div>
      <div slot="no-results"></div>
    </InfiniteLoading>
  </div>
</template>

<script>
import InfiniteLoading from "vue-infinite-loading"

export default {
  props: {
    apiUrl: String,

    filters: {
      type: Object,
      required: false,
      default() {
        return {}
      },
    },

    // <NoData /> elevation
    noElevation: Boolean,
  },

  components: { InfiniteLoading },

  data() {
    return {
      items: undefined,
      page: 0,
    }
  },

  methods: {
    infiniteHandler($state) {
      this.page++

      // Display global loader only on first load,
      // next loads will be displayed by infinite loader
      if (this.page === 1) {
        this.$setLoader(true)
      }

      const params = {
        paginate: 50,
        page: this.page,
        ...this.filters,
      }

      this.$http
        .get(this.apiUrl, { params })
        .then(({ data }) => {
          if ("meta" in data) {
            // Response with pagination
            this.items =
              this.items === undefined
                ? data.data
                : this.items.concat(data.data)
            if (data.current_page === data.last_page) {
              $state.complete()
            } else {
              $state.loaded()
            }
          } else {
            // Response without pagination
            this.items = data
            $state.complete()
          }
        })
        .finally(() => {
          if (this.page === 1) {
            this.$setLoader(false)
          }
        })
    },

    refresh() {
      this.page = 0
      this.$refs.InfiniteLoading.stateChanger.reset()
    },
  },
}
</script>