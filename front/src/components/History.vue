<template>
  <v-simple-table>
    <tbody>
      <tr v-for="(item, index) in items" :key="item.id">
        <td width="1" class="next-day-td">
          <div v-if="isNextDay(index)" class="next-day">
            <span>
              {{ $moment(item.created_at).format("DD MMMM") }}
            </span>
          </div>
        </td>
        <td>
          {{ item.comment }}
        </td>

        <td class="text-right">
          <Pts :value="item.pts" />
        </td>
      </tr>
    </tbody>
  </v-simple-table>
</template>

<script>
import Pts from "@/components/Pts"

export default {
  props: {
    items: {
      required: true,
    },
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
