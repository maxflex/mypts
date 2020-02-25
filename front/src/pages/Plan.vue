<template>
  <v-container fill-height class="justify-space-between flex-column pa-0">
    <div
      class="flex-items justify-space-between full-width pa-3 plans-controls"
    >
      <div style="flex: 1"></div>
      <div style="flex: 1; white-space: nowrap; text-align: center">
        <v-chip
          :pill="mode === modes.today"
          :outlined="mode !== modes.today"
          class="mx-2"
          @click="mode = modes.today"
          :class="{ accent: mode === modes.today }"
          >сегодня</v-chip
        >
        <v-chip
          :pill="mode === modes.tomorrow"
          :outlined="mode !== modes.tomorrow"
          @click="mode = modes.tomorrow"
          :class="{ accent: mode === modes.tomorrow }"
          class="mx-2"
          >завтра</v-chip
        >
      </div>
      <div style="flex: 1" class="text-right">
        <v-btn icon color="accent" @click="dialog = true">
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </div>
    </div>
    <div class="full-width flex-grow-1 d-flex">
      <div v-if="loading" class="align-self-center full-width text-center">
        <Loader />
      </div>
      <PlanList class="mt-2" v-else-if="items.length > 0" :items="items" />
      <div class="grey--text align-self-center full-width text-center" v-else>
        <div>
          на {{ mode === modes.today ? "сегодня" : "завтра" }} планов нет
        </div>
      </div>
    </div>
    <v-dialog v-model="dialog">
      <v-card outlined class="full-width">
        <v-card-text>
          <v-row>
            <v-col cols="12">
              <v-menu offset-y :value="autocomplete.length > 0">
                <template v-slot:activator>
                  <v-text-field
                    hide-details
                    v-model="item.comment"
                    placeholder="комментарий"
                    @keydown="search"
                  />
                </template>
                <v-list dense>
                  <v-list-item
                    @click="selectAutocomplete(item)"
                    v-for="item in autocomplete"
                    :key="item.id"
                  >
                    <div class="flex-items">
                      <span class="mr-2">{{ item.comment }}</span>
                      <Pts :value="item.pts" />
                    </div>
                  </v-list-item>
                </v-list>
              </v-menu>
            </v-col>
            <v-col cols="12">
              <v-text-field placeholder="pts" v-model="item.pts"></v-text-field>
            </v-col>
          </v-row>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn
            depressed
            class="px-5"
            color="accent"
            :loading="adding"
            @click="add"
          >
            Добавить
          </v-btn>
          <v-spacer />
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
const apiUrl = "plans"
import PlanList from "@/components/PlanList"
import Pts from "@/components/Pts"
import Loader from "@/components/Loader"
import { debounce } from "lodash"

export default {
  components: { PlanList, Pts, Loader },

  data() {
    const modes = {
      today: "today",
      tomorrow: "tomorrow",
    }
    return {
      apiUrl,
      modes,
      mode: modes.today,
      items: [],
      date: this.$moment().format("YYYY-MM-DD"),
      dialog: false,
      item: {},
      autocomplete: [],
      adding: false,
      loading: true,
    }
  },

  watch: {
    mode(newVal) {
      this.date =
        newVal === this.modes.today
          ? this.$moment().format("YYYY-MM-DD")
          : this.$moment()
              .add(1, "day")
              .format("YYYY-MM-DD")
      this.loadData()
    },
  },

  created() {
    this.loadData()

    this.search = debounce(() => {
      if (this.item.comment.length < 3) {
        this.autocomplete = []
        return
      }
      const params = {
        comment: this.item.comment,
      }
      this.$http.get("entries/autocomplete", { params }).then(r => {
        this.autocomplete = r.data
      })
    }, 300)
  },

  methods: {
    loadData() {
      const params = {
        date: this.date,
      }
      this.loading = true
      this.$http.get(apiUrl, { params }).then(r => {
        this.items = r.data
        this.loading = false
      })
    },

    selectAutocomplete(item) {
      this.item.comment = item.comment
      this.item.pts = item.pts
      this.autocomplete = []
    },

    add() {
      this.item.date = this.date
      this.adding = true
      this.$http
        .post(apiUrl, this.item)
        .then(r => this.items.unshift(r.data))
        .finally(() => {
          this.adding = false
          this.dialog = false
        })
    },
  },
}
</script>

<style lang="scss">
.plans-controls {
  position: sticky;
  top: 0;
  background: white !important;
  box-shadow: 0 0 5px 5px white;
  z-index: 1;
}
</style>
