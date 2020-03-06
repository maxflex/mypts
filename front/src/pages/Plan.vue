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
            :first-day-of-week="1"
          ></v-date-picker>
        </v-menu>
      </div>
      <div style="flex: 1; white-space: nowrap; text-align: center">
        <v-chip
          :pill="isToday"
          :outlined="!isToday"
          class="mx-2"
          @click="date = $moment().format('YYYY-MM-DD')"
          :class="{ accent: isToday }"
          >сегодня</v-chip
        >
        <v-chip
          :pill="isTomorrow"
          :outlined="!isTomorrow"
          @click="
            date = $moment()
              .add(1, 'day')
              .format('YYYY-MM-DD')
          "
          :class="{ accent: isTomorrow }"
          class="mx-2"
          >завтра</v-chip
        >
      </div>
      <div style="flex: 1" class="text-right">
        <v-btn icon color="accent" @click="addDialog()">
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </div>
    </div>
    <div class="full-width flex-grow-1 d-flex">
      <div v-if="loading" class="align-self-center full-width text-center">
        <Loader />
      </div>
      <PlanList
        @open="open"
        class="mt-2"
        v-else-if="items.length > 0"
        :items="items"
      />
      <div class="grey--text align-self-center full-width text-center" v-else>
        <div>
          на
          <span v-if="isToday">сегодня</span>
          <span v-else-if="isTomorrow">завтра</span>
          <span v-else>{{ $moment(date).format("DD MMMM") }}</span>
          планов нет
        </div>
      </div>
    </div>
    <v-dialog v-model="dialog">
      <v-card outlined class="full-width">
        <v-card-text>
          <div class="d-flex flex-column">
            <div>
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
const apiUrl = "plans"
import PlanList from "@/components/PlanList"
import Loader from "@/components/Loader"
import { debounce, cloneDeep } from "lodash"
import Expander from "@/components/Expander"

export default {
  components: { PlanList, Loader, Expander },

  data() {
    return {
      apiUrl,
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
    date() {
      // this.date =
      //   newVal === this.modes.today
      //     ? this.$moment().format("YYYY-MM-DD")
      //     : this.$moment()
      //         .add(1, "day")
      //         .format("YYYY-MM-DD")
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
        desc: this.item.desc,
        take: 10,
      }
      this.$http.get(apiUrl, { params }).then(r => {
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

    addDialog() {
      this.item = {}
      this.dialog = true
    },

    async storeOrUpdate() {
      this.adding = true
      if (this.item.id) {
        await this.$http
          .put([apiUrl, this.item.id].join("/"), this.item)
          .then(r => {
            this.items.splice()
            this.items.splice(
              this.items.findIndex(e => e.id === this.item.id),
              1,
              r.data,
            )
          })
      } else {
        this.item.date = this.date
        await this.$http
          .post(apiUrl, this.item)
          .then(r => this.items.unshift(r.data))
          .finally(() => {
            this.$store.dispatch("menu/getUnfinishedPlansCount")
          })
      }
      this.adding = false
      this.dialog = false
    },

    open(item) {
      this.item = cloneDeep(item)
      this.dialog = true
    },
  },

  computed: {
    isToday() {
      return this.date === this.$moment().format("YYYY-MM-DD")
    },
    isTomorrow() {
      return (
        this.date ===
        this.$moment()
          .add(1, "day")
          .format("YYYY-MM-DD")
      )
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
