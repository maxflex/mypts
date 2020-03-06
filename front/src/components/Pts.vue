<template>
  <span
    v-show="value != 0"
    class=""
    :class="{
      'font-weight-medium': !light,
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
    light: Boolean,
  },
}
</script>
