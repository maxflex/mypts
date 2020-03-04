<template>
  <div>
    <v-simple-table>
      <tbody>
        <tr @click="open(item)" v-for="(item, index) in items" :key="item.id">
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
        <v-card-title class="justify-center mb-2">
          <Pts
            class="headline font-weight-bold full-width text-center"
            :value="dialogItem.pts"
          />
          <div class="subtitle-2 grey--text">
            <span>{{ dialogItem.new_pts - dialogItem.pts }}</span>
            <span
              :class="{
                'secondary--text': dialogItem.pts > 0,
                'error--text': dialogItem.pts < 0,
              }"
            >
              → {{ dialogItem.new_pts }}
            </span>
          </div>
        </v-card-title>
        <v-card-text>
          <p class="body-1">
            {{ dialogItem.comment }}
          </p>
          <p v-if="dialogItem.desc" class="body-2 grey--text">
            {{ dialogItem.desc }}
          </p>
          <span class="caption grey--text">
            {{ formatDate(dialogItem.created_at) }}
          </span>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import Pts from "@/components/Pts"

export default {
  props: {
    items: {
      required: true,
    },
  },

  data() {
    return {
      dialogItem: {},
      dialog: false,
    }
  },

  components: { Pts },

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
