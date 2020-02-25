<template>
  <v-bottom-navigation grow color="accent" height="90" app>
    <v-btn exact :to="{ name: 'PageIndex' }">
      <span>Главная</span>
      <v-icon>mdi-account</v-icon>
    </v-btn>

    <v-btn :to="{ name: 'PagePlan' }">
      <span>План</span>
      <v-icon>mdi-file-document-box-check</v-icon>
    </v-btn>

    <v-btn :to="{ name: 'PageHistory' }">
      <span>История</span>
      <v-icon>mdi-history</v-icon>
    </v-btn>

    <v-btn :to="{ name: 'PageRecords' }">
      <span>Рекорды</span>
      <v-icon>mdi-trophy</v-icon>
    </v-btn>
  </v-bottom-navigation>
</template>

<script>
export default {}
</script>
