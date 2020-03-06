<template>
  <v-container fill-height class="flex-column pa-0">
    <div
      class="flex-items justify-space-between full-width pa-3 plans-controls"
    >
      <div style="flex: 1"></div>
      <div style="flex: 1; white-space: nowrap; text-align: center">
        <v-chip
          :pill="mode === modes.all"
          :outlined="mode !== modes.all"
          class="mx-2"
          @click="mode = modes.all"
          :class="{ accent: mode === modes.all }"
        >
          все
        </v-chip>
        <v-chip
          :pill="mode === modes.byDay"
          :outlined="mode !== modes.byDay"
          class="mx-2"
          @click="mode = modes.byDay"
          :class="{ accent: mode === modes.byDay }"
        >
          дни
        </v-chip>
      </div>
      <div style="flex: 1" class="text-right"></div>
    </div>
    <div class="full-width flex-grow-1">
      <DisplayList :api-url="API_URL" :key="mode" :filters="{ mode }">
        <template v-slot:items="{ items }">
          <component :is="mode" :items="items" />
        </template>
      </DisplayList>
    </div>
  </v-container>
</template>

<script>
import DisplayList from "@/components/DisplayList"
import { API_URL, All, ByDay } from "@/components/History/index.js"

export default {
  components: { DisplayList, All, ByDay },

  data() {
    const modes = {
      all: "all",
      byDay: "by-day",
    }
    return {
      API_URL,
      modes,
      mode: modes.all,
    }
  },
}
</script>
