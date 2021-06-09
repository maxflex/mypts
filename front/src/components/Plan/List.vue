<template>
  <div class="full-width">
    <v-simple-table class="plans-table">
      <tbody>
        <tr
          v-for="item in items"
          :key="item.id"
          :class="{ 'plans-table_finished': item.is_finished }"
          v-touch="{
            left: () => $refs.DeleteDialog.open(item),
          }"
        >
          <td @click="$emit('open', item)">
            <span class="plans-table__plan-comment">
              {{ item.comment }}
              <span class="grey--text ml-1 caption" v-if="item.time">
                {{ item.time | time }}
              </span>
            </span>
          </td>
          <td width="60">
            <span class="font-weight-medium">
              {{ item.pts }}
            </span>
          </td>
          <td class="text-right" @click="toggle(item)" width="56">
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
    <DeleteDialog @deleted="deleted" label="план" ref="DeleteDialog" />
  </div>
</template>

<script>
import DeleteDialog from "@/components/DeleteDialog"
import { API_URL } from "./"

export default {
  props: {
    items: {
      required: true,
    },
  },

  components: { DeleteDialog },

  methods: {
    async toggle(item) {
      await this.$http.put([API_URL, "toggle", item.id].join("/"))
      this.$store.dispatch("menu/getUnfinishedPlansCount")
    },

    deleted(item) {
      const index = this.items.findIndex(e => e.id === item.id)
      this.items.splice(index, 1)
      this.$http.delete([API_URL, item.id].join("/"))
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
