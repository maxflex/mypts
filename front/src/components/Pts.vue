<template>
  <span
    v-show="!hideZero || value != 0"
    :class="{
      'font-weight-medium': !bold && !light,
      'font-weight-bold': bold,
      'grey--text': value === 0,
      'secondary--text': value > 0,
      'error--text': value < 0,
    }"
  >
    <span v-if="value > 0">+</span>{{ value | formatPts }}
  </span>
</template>

<script>
export default {
  props: {
    value: {
      required: true,
    },
    hideZero: Boolean,
    light: Boolean,
    bold: Boolean,
  },
}
</script>
