<template>
  <v-card flat class="text-center py-5">
    <v-card-text>
      <v-icon
        class="d-block mb-5 grey--text text--lighten-1"
        style="font-size: 42px !important"
        v-text="icon"
      >
      </v-icon>
      {{ text }}
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  props: {
    icon: {
      type: String,
      default: "mdi-file-outline",
    },

    text: {
      type: String,
      default: "нет данных",
    },
  },
}
</script>
