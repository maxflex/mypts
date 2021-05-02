<template>
  <v-bottom-navigation grow color="accent" height="75" app>
    <v-btn exact :to="{ name: 'PageIndex' }">
      <v-icon>mdi-account</v-icon>
    </v-btn>

    <v-btn :to="{ name: 'PagePlan' }">
      <v-badge
        overlap
        :content="$store.state.menu.unfinishedPlansCount"
        color="accent"
        bordered
        :value="$store.state.menu.unfinishedPlansCount > 0"
      >
        <v-icon>mdi-calendar-check</v-icon>
      </v-badge>
    </v-btn>

    <v-btn :to="{ name: 'PageGraph' }">
      <v-icon>mdi-chart-timeline-variant</v-icon>
    </v-btn>

    <v-btn :to="{ name: 'PageHistory' }">
      <v-icon>mdi-history</v-icon>
    </v-btn>

    <v-btn :to="{ name: 'PageRules' }">
      <v-icon>mdi-file-document-box</v-icon>
    </v-btn>
  </v-bottom-navigation>
</template>

<script>
export default {}
</script>
