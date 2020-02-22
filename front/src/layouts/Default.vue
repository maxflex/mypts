<template>
  <div id="app">
    <v-app>
      <v-content>
        <v-container fill-height>
          <router-view></router-view>
        </v-container>
      </v-content>
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
    </v-app>
  </div>
</template>

<script>
import Menu from "@/components/Menu"

export default {
  components: { Menu },
}
</script>
