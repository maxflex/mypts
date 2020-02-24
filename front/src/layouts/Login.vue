<template>
  <v-content class="login-page">
    <v-container fill-height fluid class="justify-center">
      <v-card style="max-width: 400px" class="full-width">
        <v-card-title class="justify-center">
          <v-img src="/img/police.png" max-width="30%" />
        </v-card-title>
        <v-card-text>
          <v-stepper non-linear v-model="step" class="elevation-0">
            <v-stepper-items>
              <v-stepper-content :step="1">
                <v-text-field
                  outlined
                  label="Предъявите документы"
                  class="mt-1"
                  v-model="credentials.docs"
                  @keydown.enter="step = 2"
                />
                <div class="d-flex justify-space-between mt-10">
                  <v-spacer />
                  <v-btn color="accent" depressed @click="step = 2">
                    далее
                  </v-btn>
                </div>
              </v-stepper-content>
              <v-stepper-content :step="2">
                <v-text-field
                  outlined
                  label="А?"
                  v-model="credentials.password"
                  @keydown.enter="signIn()"
                  type="password"
                  class="mt-1"
                />
                <div class="d-flex justify-space-between mt-10">
                  <a @click="step = 1" class="subtitle-2">Назад</a>
                  <v-btn
                    depressed
                    color="accent"
                    @click="signIn()"
                    :loading="loading"
                    >далее</v-btn
                  >
                </div>
              </v-stepper-content>

              <v-stepper-content :step="3">
                <div class="headline text-center">
                  Завтра в милицию пойдёшь
                </div>
              </v-stepper-content>
            </v-stepper-items>
          </v-stepper>
        </v-card-text>
      </v-card>
    </v-container>
  </v-content>
</template>

<script>
export default {
  data() {
    return {
      step: 1,
      loading: false,
      credentials: {
        docs: "",
        password: "",
      },
    }
  },

  methods: {
    signIn() {
      this.loading = true
      this.$store
        .dispatch("auth/signIn", this.credentials)
        .then(() => this.$router.push("/"))
        .catch(() => (this.step = 3))
        .finally(() => {
          this.loading = false
        })
    },
  },
}
</script>

<style lang="scss">
.login-page {
  background-image: linear-gradient(180deg, #f093fb 0%, #f5576c 100%);
  & .v-card {
    background-color: rgba(white, 0.5) !important;
  }

  & .v-stepper {
    background-color: transparent !important;
  }
}
</style>
