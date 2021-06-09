<template>
  <v-container fill-height class="justify-space-between flex-column pa-0">
    <div
      class="flex-items justify-space-between full-width pa-3 plans-controls"
    >
      <div style="flex: 1">
        <v-menu>
          <template v-slot:activator="{ on }">
            <v-btn icon color="accent" v-on="on">
              <v-icon>
                mdi-calendar-blank
              </v-icon>
            </v-btn>
          </template>
          <v-date-picker
            no-title
            v-model="date"
            :events="events"
            event-color="success lighten-2"
            :first-day-of-week="1"
          ></v-date-picker>
        </v-menu>
      </div>
      <div
        style="flex: 1; white-space: nowrap; text-align: center"
        class="grey--text"
      >
        <template v-if="isToday">
          сегодня,
        </template>
        <template v-else-if="isTomorrow">
          завтра,
        </template>
        {{ $moment(date).format("dddd D MMMM") }}
      </div>
      <div style="flex: 1" class="text-right">
        <v-btn icon color="accent" @click="addDialog()">
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </div>
    </div>
    <div class="full-width d-flex flex-grow-1">
      <div v-if="loading" class="full-width d-flex justify-center align-center">
        <Loader />
      </div>
      <div class="full-width" v-else>
        <PlanList
          @open="open"
          class="mt-2"
          v-if="items.length > 0"
          :items="items"
        />
        <div class="grey--text py-12 my-12 text-center" v-else>
          планов нет
        </div>
        <template v-if="isToday && weekItems.length > 0">
          <div class="grey--text text-center mt-12">
            на этой неделе
          </div>
          <PlanList @open="open" class="mt-2" :items="weekItems" />
        </template>
      </div>
    </div>
    <v-dialog v-model="dialog">
      <v-card outlined class="full-width">
        <v-card-text>
          <div
            class="body-1 grey--text pt-5"
            v-if="item.id && item.date !== todayDate"
          >
            {{ $moment(item.date).format("dddd D MMMM") }}
          </div>
          <div class="d-flex flex-column">
            <div>
              <v-menu offset-y>
                <template v-slot:activator="{ on }">
                  <v-text-field
                    hide-details
                    v-model="item.comment"
                    placeholder="комментарий"
                    v-on="on"
                    @keydown="search"
                  />
                </template>
                <v-list dense v-if="autocomplete.length > 0">
                  <v-list-item
                    @click="selectAutocomplete(item)"
                    v-for="item in autocomplete"
                    :key="item.id"
                  >
                    <div class="flex-items">
                      <span class="mr-2">{{ item.comment }}</span>
                      <span class="font-weight-bold">{{ item.pts }}</span>
                    </div>
                  </v-list-item>
                </v-list>
              </v-menu>
            </div>
            <div>
              <v-text-field
                hide-details
                placeholder="pts"
                v-model="item.pts"
              ></v-text-field>
            </div>
            <Expander>
              <v-text-field
                hide-details
                v-model="item.time"
                placeholder="время"
                v-mask="'##:##'"
              />
              <v-textarea
                hide-details
                rows="3"
                v-model="item.desc"
                placeholder="описание"
              />
            </Expander>
          </div>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn
            depressed
            class="px-5"
            color="accent"
            :loading="adding"
            @click="storeOrUpdate"
          >
            {{ item.id ? "Редактировать" : "Добавить" }}
          </v-btn>
          <v-spacer />
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import { PlanList, MODEL_DEFAULTS, API_URL } from "@/components/Plan"
import Loader from "@/components/Loader"
import { debounce, cloneDeep, uniqBy } from "lodash"
import Expander from "@/components/Expander"

export default {
  components: { PlanList, Loader, Expander },

  data() {
    return {
      items: [],
      weekItems: [],
      events: [],
      date: this.$moment().format("YYYY-MM-DD"),
      dialog: false,
      item: MODEL_DEFAULTS,
      autocomplete: [],
      adding: false,
      loading: true,
    }
  },

  watch: {
    date() {
      this.loadData()
    },
  },

  created() {
    this.todayDate = this.$moment().format("YYYY-MM-DD")
    this.endOfWeekDate = this.$moment()
      .endOf("week")
      .format("YYYY-MM-DD")
    this.tomorrowDate = this.$moment()
      .add(1, "day")
      .format("YYYY-MM-DD")

    this.loadData()
    this.loadWeekData()

    // TODO: lazy-load on calendar open
    this.loadEvents()

    this.search = debounce(() => {
      if (this.item.comment.length < 3) {
        this.autocomplete = []
        return
      }
      const params = {
        comment: this.item.comment,
        desc: this.item.desc,
        take: 10,
      }
      this.$http.get(API_URL, { params }).then(r => {
        this.autocomplete = uniqBy(r.data, "comment")
      })
    }, 300)
  },

  methods: {
    loadData() {
      const params = {
        date: this.date,
      }
      this.loading = true
      this.$http.get(API_URL, { params }).then(r => {
        this.items = r.data
        this.loading = false
      })
    },

    loadWeekData() {
      if (this.isEndOfWeek) {
        return
      }
      const params = {
        interval: {
          start: this.tomorrowDate,
          end: this.endOfWeekDate,
        },
      }
      this.$http.get(API_URL, { params }).then(r => (this.weekItems = r.data))
    },

    loadEvents() {
      this.$http.get(API_URL + "/events").then(r => (this.events = r.data))
    },

    selectAutocomplete(item) {
      this.item.comment = item.comment
      this.item.pts = item.pts
      this.autocomplete = []
    },

    addDialog() {
      this.item = {
        ...MODEL_DEFAULTS,
        date: this.date,
      }
      this.dialog = true
    },

    open(item) {
      this.item = cloneDeep(item)
      this.dialog = true
    },

    async storeOrUpdate() {
      this.adding = true
      if (this.item.id) {
        await this.$http
          .put([API_URL, this.item.id].join("/"), this.item)
          .then(r => {
            this.items.splice()
            this.items.splice(
              this.items.findIndex(e => e.id === this.item.id),
              1,
              r.data,
            )
          })
      } else {
        await this.$http.post(API_URL, this.item).then(r => {
          const item = r.data
          if (item.date === this.todayDate) {
            this.$store.dispatch("menu/getUnfinishedPlansCount")
          } else {
            this.loadWeekData()
          }
          this.items.unshift(item)
        })
      }
      this.adding = false
      this.dialog = false
    },
  },

  computed: {
    isToday() {
      return this.date === this.todayDate
    },
    isTomorrow() {
      return this.date === this.tomorrowDate
    },
    isEndOfWeek() {
      return this.date === this.endOfWeekDate
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
