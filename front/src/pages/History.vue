<template>
  <v-row>
    <v-col class="pa-0" align="center">
      <DisplayList :api-url="apiUrl">
        <template v-slot:items="{ items }">
          <History :items="items" />
        </template>
      </DisplayList>
    </v-col>
  </v-row>
</template>

<script>
const apiUrl = "entries"
import DisplayList from "@/components/DisplayList"
import History from "@/components/History"

export default {
  components: { DisplayList, History },

  data() {
    return {
      apiUrl,
    }
  },
}
</script>
