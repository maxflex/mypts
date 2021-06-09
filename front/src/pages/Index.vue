<template>
  <v-container fill-height>
    <v-btn
      :to="{ name: 'PageAchievements' }"
      class="btn-top btn-top_left"
      icon
      color="accent"
    >
      <v-icon>mdi-medal-outline</v-icon>
    </v-btn>
    <div class="btn-top btn-top_center">
      <v-icon color="secondary" small>mdi-trophy</v-icon>
      <div class="font-weight-bold ml-1 secondary--text">
        {{ $store.state.auth.user.record.pts | formatPts }}
      </div>
    </div>

    <v-btn
      class="btn-top btn-top_right"
      icon
      color="accent"
      @click="dialog = true"
    >
      <v-icon>mdi-plus</v-icon>
    </v-btn>
    <v-row>
      <v-col align="center">
        <Loader v-if="pts === undefined" />
        <div v-else>
          <div class="justify-center flex-items relative">
            <v-scale-transition>
              <div
                v-if="$store.state.auth.user.record.is_new"
                class="new-record"
              >
                <v-chip color="success" small>
                  <v-icon small class="mr-1">mdi-trophy</v-icon>
                  новый рекорд
                </v-chip>
              </div>
            </v-scale-transition>
            <div class="display-4 font-weight-bold mr-2 accent--text">
              {{ pts.currentPts | formatPts }}
            </div>
            <span class="subtitle-1 grey--text">
              pts
            </span>
          </div>
          <div class="mt-10 justify-center flex-items">
            <Pts class="mr-1" :value="pts.today" />
            <span class="grey--text">сегодня</span>
          </div>
          <div
            class="mt-10 caption justify-center flex-items"
            style="opacity: .5"
          >
            <Pts class="mr-1" :value="pts.yesterday" />
            <span class="grey--text">вчера</span>
          </div>
        </div>
      </v-col>
    </v-row>
    <div class="quick-controls">
      <div>
        <v-btn
          v-for="i in [1, 2, 3]"
          :key="i"
          color="success"
          rounded
          outlined
          small
          @click="quickAdd(i)"
        >
          +{{ i }}
        </v-btn>
      </div>
      <div>
        <v-btn
          v-for="i in [1, 2, 3]"
          :key="i"
          color="error"
          rounded
          outlined
          small
          @click="quickAdd(i * -1)"
        >
          -{{ i }}
        </v-btn>
      </div>
    </div>
    <v-dialog v-model="dialog">
      <v-card outlined class="full-width">
        <v-card-text>
          <div class="d-flex flex-column">
            <div>
              <v-menu offset-y>
                <template v-slot:activator="{ on }">
                  <v-text-field
                    hide-details
                    v-model="item.comment"
                    placeholder="комментарий"
                    @keydown="search"
                    v-on="on"
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
                      <Pts :value="item.pts" />
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
const apiUrl = "pts"
import Pts from "@/components/Pts"
import Loader from "@/components/Loader"
import Expander from "@/components/Expander"
import { debounce, uniqBy } from "lodash"

export default {
  components: { Pts, Loader, Expander },

  data() {
    return {
      pts: undefined,
      dialog: false,
      item: {},
      autocomplete: [],
      adding: false,
    }
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
      this.$http.get("entries", { params }).then(r => {
        this.autocomplete = uniqBy(r.data.data, "comment")
      })
    }, 300)
  },

  methods: {
    loadData() {
      this.pts = undefined
      this.$http.get(apiUrl).then(r => (this.pts = r.data))
      // check if new record
      this.$store.dispatch("auth/getLoggedUser")
    },

    selectAutocomplete(item) {
      this.item.comment = item.comment
      this.item.pts = item.pts
      this.autocomplete = []
    },

    add() {
      this.adding = true
      this.$http
        .post("entries", this.item)
        .then(() => this.loadData())
        .finally(() => {
          this.adding = false
          this.dialog = false
          this.item = {}
        })
    },

    quickAdd(pts) {
      this.item = {
        pts,
        comment: pts > 0 ? "Быстрое вознаграждение" : "Быстрое наказание",
      }
      this.add()
    },

    toggleVacation() {
      this.$http
        .put("profile", {
          on_vacation: !this.$store.state.auth.user.on_vacation,
        })
        .then(r => {
          this.$store.dispatch("auth/setUser", r.data)
        })
    },
  },
}
</script>

<style lang="scss">
.new-record {
  position: absolute;
  width: 100%;
  text-align: center;
  left: 0;
  top: -40px;
}
.vacation-status {
  $size: 20px;
  height: $size;
  width: $size;
  border-radius: 50%;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  &_inner {
    height: #{$size / 2};
    width: #{$size / 2};
    background: inherit;
    z-index: 1;
  }
  &_outer {
    &:before {
      content: "";
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      border-radius: 50%;
      background: rgba(white, 0.75);
    }
  }
}
.quick-controls {
  position: absolute;
  bottom: 15%;
  left: 0%;
  width: 100%;
  text-align: center;
  & > div {
    margin-bottom: 10px;
    & > *:not(:last-child) {
      margin-right: 10px;
    }
  }
}
</style>
