<template>
  <div id="app">
    <v-app>
      <template v-if="loaded">
        <LayoutDefault v-if="isLoggedIn" />
        <LoginLayout v-else />
      </template>
    </v-app>
  </div>
</template>

<script>
import LayoutDefault from "@/layouts/Default"
import LoginLayout from "@/layouts/Login"
import { mapGetters } from "vuex"

export default {
  components: { LayoutDefault, LoginLayout },

  data() {
    return {
      loaded: false,
    }
  },

  async created() {
    await this.$store.dispatch("auth/getLoggedUser")
    this.loaded = true
  },

  computed: mapGetters("auth", ["isLoggedIn"]),
}
</script>
