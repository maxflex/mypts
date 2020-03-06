<template>
  <v-simple-table>
    <tbody>
      <tr v-for="item in items" :key="item.date">
        <td class="grey--text">
          {{ $moment(item.date).format("DD.MM") }}
        </td>
        <td class="text-right">
          <Pts :value="item.pts_minus_total" light />
        </td>
        <td class="text-right">
          <Pts :value="item.pts_plus_total" light />
        </td>
        <td class="text-right">
          <Pts :value="item.pts_total" bold />
        </td>
      </tr>
    </tbody>
  </v-simple-table>
</template>

<script>
import Pts from "@/components/Pts"

export default {
  name: "by-day",

  components: { Pts },

  props: {
    items: {
      requried: true,
      type: Array,
    },
  },
}
</script>
