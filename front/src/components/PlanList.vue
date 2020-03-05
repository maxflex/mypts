<template>
  <div class="full-width">
    <v-simple-table class="plans-table">
      <tbody>
        <tr
          v-for="item in items.filter(e => !('deleted' in e))"
          :key="item.id"
          :class="{ 'plans-table_finished': item.is_finished }"
          v-touch="{
            left: () => confirmDelete(item),
          }"
        >
          <td @click="$emit('open', item)">
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
    <v-dialog v-model="dialog">
      <v-card>
        <v-card-title>
          Удалить план?
        </v-card-title>
        <v-card-text>
          {{ dialogItem.comment }}
        </v-card-text>
        <v-card-actions>
          <v-spacer> </v-spacer>
          <v-btn text depressed @click="dialog = false">отмена</v-btn>
          <v-btn
            color="accent"
            depressed
            @click="deleteItem()"
            :loading="deleting"
            >Да</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
const apiUrl = "plans"

export default {
  props: {
    items: {
      required: true,
    },
  },

  data() {
    return {
      dialog: false,
      dialogItem: {},
      deleting: false,
    }
  },

  methods: {
    async toggle(item) {
      await this.$http.put([apiUrl, "toggle", item.id].join("/"))
      this.$store.dispatch("menu/getUnfinishedPlansCount")
    },

    confirmDelete(item) {
      this.dialogItem = item
      this.dialog = true
    },

    async deleteItem() {
      this.deleting = true
      await this.$http.delete([apiUrl, this.dialogItem.id].join("/"))
      this.dialogItem.deleted = true
      this.dialog = false
      this.deleting = false
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
