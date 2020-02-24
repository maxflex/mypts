<template>
  <v-content>
    <v-container fill-height fluid>
      <router-view></router-view>
    </v-container>
    <Menu />
    <v-snackbar
      v-model="$store.state.snackBar.show"
      :timeout="6000"
      :color="$store.state.snackBar.color"
      top
    >
      <div
        class="full-width text-center"
        v-html="$store.state.snackBar.text"
      ></div>
    </v-snackbar>
  </v-content>
</template>

<script>
import Menu from "@/components/Menu"

export default {
  components: { Menu },
}
</script>
