<template>
  <v-bottom-navigation grow color="accent" height="90" app>
    <v-btn exact :to="{ name: 'PageIndex' }">
      <span>Главная</span>
      <v-icon>mdi-account</v-icon>
    </v-btn>

    <v-btn :to="{ name: 'PagePlan' }">
      <span>План</span>
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

    <v-btn :to="{ name: 'PageHistory' }">
      <span>История</span>
      <v-icon>mdi-history</v-icon>
    </v-btn>

    <v-btn :to="{ name: 'PageRules' }">
      <span>Правила</span>
      <v-icon>mdi-file-document-box</v-icon>
    </v-btn>
  </v-bottom-navigation>
</template>

<script>
export default {}
</script>
