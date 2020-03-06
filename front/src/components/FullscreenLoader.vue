<template>
  <div class="fullscreen-loader">
    <v-progress-circular
      color="accent"
      indeterminate
      size="64"
    ></v-progress-circular>
  </div>
</template>

<style lang="scss">
.fullscreen-loader {
  position: fixed;
  left: 0;
  top: -20px;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
