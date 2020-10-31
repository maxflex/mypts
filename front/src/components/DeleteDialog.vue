<template>
  <v-dialog v-model="dialog">
    <v-card>
      <v-card-title> Удалить {{ label }}? </v-card-title>
      <v-card-text v-if="item !== undefined">
        {{ item.comment }}
      </v-card-text>
      <v-card-actions>
        <v-spacer> </v-spacer>
        <v-btn text depressed @click="dialog = false">отмена</v-btn>
        <v-btn color="accent" depressed @click="confirm()" :loading="loading">
          Да
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      default: "запись",
    },
  },

  data() {
    return {
      dialog: false,
      loading: false,
      item: undefined,
    }
  },

  methods: {
    open(item) {
      this.item = item
      this.loading = false
      this.dialog = true
    },

    confirm() {
      this.dialog = false
      this.$emit("deleted", this.item)
    },
  },
}
</script>
