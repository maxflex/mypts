<template>
  <v-container fill-height>
    <v-btn
      :to="{ name: 'PageAchievements' }"
      class="btn-top btn-top_left"
      icon
      color="accent"
    >
      <v-icon>mdi-trophy</v-icon>
    </v-btn>
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
              <div v-if="isNewRecord" class="new-record">
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
import { debounce } from "lodash"

export default {
  components: { Pts, Loader, Expander },

  data() {
    return {
      pts: undefined,
      dialog: false,
      item: {},
      autocomplete: [],
      adding: false,
      isNewRecord: false,
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
        this.autocomplete = r.data.data
      })
    }, 300)
  },

  methods: {
    loadData() {
      this.pts = undefined
      this.$http.get(apiUrl).then(r => {
        this.pts = r.data
        this.$nextTick(() => {
          this.isNewRecord = r.data.isNewRecord
          this.$store.commit("menu/setIsNewRecord", this.isNewRecord)
        })
      })
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
</style>
