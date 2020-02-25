<template>
  <v-simple-table class="plans-table">
    <tbody>
      <tr
        v-for="item in items"
        :key="item.id"
        :class="{ 'plans-table_finished': item.is_finished }"
      >
        <td>
          <span class="plans-table__plan-comment">
            {{ item.comment }}
          </span>
        </td>
        <td>
          <span class="font-weight-medium">
            {{ item.pts }}
          </span>
        </td>
        <td class="text-right" @click="toggle(item)">
          <v-checkbox
            v-model="item.is_finished"
            color="accent"
            hide-details
            class="pa-0 ma-0"
          ></v-checkbox>
        </td>
      </tr>
    </tbody>
  </v-simple-table>
</template>

<script>
const apiUrl = "plans"

export default {
  props: {
    items: {
      required: true,
    },
  },

  methods: {
    async toggle(item) {
      await this.$http.put([apiUrl, item.id].join("/"))
      this.$store.dispatch("menu/getUnfinishedPlansCount")
    },
  },
}
</script>

<style lang="scss">
.plans-table {
  width: 100%;
  & td {
    transition: all linear 0.8s;
  }
  & .v-input__slot {
    justify-content: flex-end;
    & .v-input--selection-controls__input {
      margin: 0 !important;
    }
  }
  &_finished {
    & td {
      opacity: 0.3;
      & .plans-table__plan-comment {
        text-decoration: line-through;
      }
    }
  }
}
</style>
