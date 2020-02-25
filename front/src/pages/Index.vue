<template>
  <v-container fill-height>
    <v-btn class="add-btn" icon color="accent" @click="dialog = true">
      <v-icon>mdi-plus</v-icon>
    </v-btn>
    <v-row>
      <v-col align="center">
        <Loader v-if="pts === undefined" />
        <div v-else>
          <div class="justify-center flex-items">
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
const apiUrl = "pts"
import Pts from "@/components/Pts"
import Loader from "@/components/Loader"
import { debounce } from "lodash"

export default {
  components: { Pts, Loader },

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
      }
      this.$http.get("entries/autocomplete", { params }).then(r => {
        this.autocomplete = r.data
      })
    }, 300)
  },

  methods: {
    loadData() {
      this.pts = undefined
      this.$http.get(apiUrl).then(r => (this.pts = r.data))
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
        .then(r => this.loadData())
        .finally(() => {
          this.adding = false
          this.dialog = false
        })
    },
  },
}
</script>

<style lang="scss">
.add-btn {
  position: absolute !important;
  right: 12px;
  top: 12px;
}
</style>
