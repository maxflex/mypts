<template>
  <v-bottom-navigation color="accent">
    <v-btn :to="{ name: 'PageIndex' }">
      <span>Главная</span>
      <v-icon>mdi-account</v-icon>
    </v-btn>

    <v-btn>
      <span>История</span>
      <v-icon>mdi-history</v-icon>
    </v-btn>

    <v-btn :to="{ name: 'PageAdd' }">
      <span>Добавить</span>
      <v-icon>mdi-plus</v-icon>
    </v-btn>
  </v-bottom-navigation>
</template>

<script>
export default {}
</script>

<style></style>
