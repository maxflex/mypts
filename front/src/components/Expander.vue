<template>
  <div>
    <a
      class="caption d-block pt-4"
      v-if="!isExpanded"
      @click="isExpanded = true"
    >
      {{ text }}
    </a>
    <slot v-else> </slot>
  </div>
</template>

<script>
export default {
  props: {
    text: {
      type: String,
      default: "дополнительные параметры",
    },
    expanded: Boolean,
  },

  data() {
    return {
      // avoid mutating prop directly
      isExpanded: this.expanded,
    }
  },
}
</script>
