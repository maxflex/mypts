<template>
  <div>
    <v-simple-table>
      <tbody>
        <tr
          @click="open(item)"
          v-for="(item, index) in items"
          :key="item.id"
          v-touch="{
            left: () => $refs.DeleteDialog.open(item),
          }"
        >
          <td width="1" class="next-day-td">
            <div v-if="isNextDay(index)" class="next-day">
              <span>
                {{ $moment(item.created_at).format("DD MMMM") }}
              </span>
            </div>
          </td>
          <td>
            {{ item.comment }}
            <v-icon small color="grey" v-if="item.desc">
              mdi-information-outline
            </v-icon>
          </td>
          <td class="text-right">
            <Pts :value="item.pts" />
          </td>
        </tr>
      </tbody>
    </v-simple-table>
    <v-dialog v-model="dialog">
      <v-card outlined>
        <v-card-title class="justify-center">
          <Pts class="headline" :value="dialogItem.pts" />
        </v-card-title>
        <v-card-text>
          <p class="body-1">
            {{ dialogItem.comment }}
          </p>
          <p
            v-if="dialogItem.desc"
            class="body-2 grey--text"
            v-html="dialogItem.desc_html"
          ></p>
          <span class="caption grey--text">
            {{ formatDate(dialogItem.created_at) }}
          </span>
        </v-card-text>
      </v-card>
    </v-dialog>
    <DeleteDialog @deleted="deleted" ref="DeleteDialog" />
  </div>
</template>

<script>
import DeleteDialog from "@/components/DeleteDialog"
import Pts from "@/components/Pts"
import { API_URL } from "@/components/Entry"

export default {
  name: "all",

  props: {
    items: {
      required: true,
    },
  },

  components: { Pts, DeleteDialog },

  data() {
    return {
      dialogItem: {},
      dialog: false,
    }
  },

  methods: {
    isNextDay(index) {
      if (index <= 0) {
        return false
      }

      return this.getDate(index) != this.getDate(index - 1)
    },

    getDate(index) {
      return this.$moment(this.items[index].created_at).format("YYYY-MM-DD")
    },

    formatDate(date) {
      return this.$moment(date).format("DD.MM.YY в HH:mm")
    },

    open(item) {
      this.dialogItem = item
      this.dialog = true
    },

    deleted(item) {
      const index = this.items.findIndex(e => e.id === item.id)
      this.items.splice(index, 1)
      this.$http.delete([API_URL, item.id].join("/"))
    },
  },
}
</script>

<style lang="scss">
.next-day {
  width: calc(100vw - 24px);
  position: absolute;
  top: -12px;
  left: 0;
  text-align: center;
  & > span {
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
    border-radius: 8px;
    background: #f3377a;
    color: white;
    padding: 2px 6px;
  }
  &-td {
    position: relative;
    padding: 0 !important;
  }
}
</style>
