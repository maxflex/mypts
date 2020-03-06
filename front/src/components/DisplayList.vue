<template>
  <div class="fill-height">
    <div v-if="items !== undefined">
      <slot name="items" :items="items" v-if="items.length > 0"></slot>
    </div>
    <div
      v-if="items === undefined"
      class="d-flex fill-height align-center justify-center"
    >
      <Loader />
    </div>
    <InfiniteLoading
      spinner="spiral"
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
import Loader from "@/components/Loader"

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
  },

  components: { InfiniteLoading, Loader },

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
            if (data.meta.current_page === data.meta.last_page) {
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
