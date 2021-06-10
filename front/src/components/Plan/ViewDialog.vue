<template>
  <v-dialog v-model="dialog">
    <v-card outlined>
      <v-card-title class="justify-center">
        <Pts class="headline" :value="item.pts" />
      </v-card-title>
      <v-card-text class="pb-3">
        <p class="body-1 font-weight-medium text-center">
          {{ item.comment }}
        </p>
        <p
          v-if="item.desc"
          class="body-2 grey--text"
          v-html="item.desc_html"
        ></p>
        <v-divider class="mb-3" />
        <div class="caption grey--text d-flex justify-center">
          <v-icon color="grey" small class="mr-1">
            mdi-clock-outline
          </v-icon>
          <template v-if="completed">
            {{ item.created_at | dateTime }}
          </template>
          <template v-else>
            <template v-if="isToday">
              сегодня
            </template>
            <template v-else-if="isTomorrow">
              завтра
            </template>
            <template v-else>
              {{ $moment(item.date).format("dddd D MMMM") }}
            </template>
            <template v-if="item.time"> в {{ item.time | time }} </template>
          </template>
        </div>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
import Pts from "@/components/Pts"

export default {
  components: { Pts },

  props: {
    completed: Boolean,
  },

  data() {
    return {
      dialog: false,
      item: {},
    }
  },

  methods: {
    open(item) {
      this.item = item
      this.dialog = true
    },
  },

  computed: {
    isToday() {
      return this.item.date === this.$moment().format("YYYY-MM-DD")
    },
    isTomorrow() {
      return (
        this.item.date ===
        this.$moment()
          .add(1, "day")
          .format("YYYY-MM-DD")
      )
    },
  },
}
</script>
