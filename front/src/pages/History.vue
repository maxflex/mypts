<template>
  <v-row>
    <v-col align="center">
      <!-- <v-progress-circular
        v-if="pts === undefined"
        color="accent"
        indeterminate
        size="64"
      ></v-progress-circular> -->
      <div>
        <DisplayList :api-url="apiUrl">
          <template v-slot:items="{ items }">
            <History :items="items" />
          </template>
        </DisplayList>
      </div>
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
