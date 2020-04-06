<template>
  <v-container fill-height class="justify-space-between flex-column">
    <div class="flex-items full-width mb-3">
      <v-btn @click="$router.back()" icon color="grey">
        <v-icon>mdi-arrow-left</v-icon>
      </v-btn>
      <div class="flex-grow-1">
        <div v-if="record !== undefined" class="justify-center flex-items">
          <v-icon color="secondary" small>mdi-trophy</v-icon>
          <div class="font-weight-bold ml-1 secondary--text">
            {{ record.pts | formatPts }}
          </div>
        </div>
      </div>
      <div class="text-right">
        <v-btn icon color="accent" @click="addDialog()">
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </div>
    </div>
    <div class="full-width flex-grow-1 d-flex">
      <div v-if="loading" class="align-self-center full-width text-center">
        <Loader />
      </div>
      <div v-else class="flex-items full-width align-start">
        <Item
          v-for="item in items"
          :key="item.id"
          :item="item"
          @click.native="view(item)"
        />
      </div>
    </div>
    <v-dialog v-model="achieveDialog">
      <v-card outlined>
        <v-card-title class="justify-center">
          <Pts class="headline" :value="item.pts" />
        </v-card-title>
        <v-card-text>
          <p class="body-1">
            {{ item.name }}
          </p>
          <p
            v-if="item.desc"
            class="body-2 grey--text"
            v-html="item.desc_html"
          ></p>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn
            v-if="item.achieved_at === null"
            depressed
            class="px-5"
            color="accent"
            :loading="dialogLoading"
            @click="achieve()"
          >
            Достигнуть
          </v-btn>
          <span class="caption grey--text" v-else>
            достигнуто {{ $moment(item.achieved_at).format("DD MMMM YYYY") }}
          </span>
          <v-spacer />
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialog">
      <v-card outlined class="full-width">
        <v-card-text>
          <div class="d-flex flex-column">
            <div>
              <v-text-field
                hide-details
                v-model="item.name"
                placeholder="название"
              />
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
import Loader from "@/components/Loader"
import { API_URL, Item } from "@/components/Achievement"
import Expander from "@/components/Expander"
import { cloneDeep } from "lodash"
import Pts from "@/components/Pts"

export default {
  components: { Pts, Loader, Item, Expander },

  data() {
    return {
      items: [],
      dialog: false,
      loading: true,
      adding: false,
      item: {},
      achieveDialog: false,
      dialogLoading: false,
      record: undefined,
    }
  },

  created() {
    this.loadData()
    this.loadRecord()
  },

  methods: {
    loadData() {
      this.loading = true
      this.$http.get(API_URL).then((r) => {
        this.items = r.data
        this.loading = false
      })
    },

    loadRecord() {
      this.$http.get("records").then((r) => (this.record = r.data))
    },

    addDialog() {
      this.item = {}
      this.dialog = true
    },

    async storeOrUpdate() {
      this.adding = true
      if (this.item.id) {
        await this.$http
          .put([API_URL, this.item.id].join("/"), this.item)
          .then((r) => {
            this.items.splice(
              this.items.findIndex((e) => e.id === this.item.id),
              1,
              r.data,
            )
          })
      } else {
        await this.$http
          .post(API_URL, this.item)
          .then((r) => this.items.unshift(r.data))
      }
      this.adding = false
      this.dialog = false
    },

    open(item) {
      this.item = cloneDeep(item)
      this.dialog = true
    },

    view(item) {
      this.item = cloneDeep(item)
      this.achieveDialog = true
    },

    achieve() {
      this.dialogLoading = true
      this.$http.put([API_URL, "achieve", this.item.id].join("/")).then((r) => {
        this.items.splice(
          this.items.findIndex((e) => e.id === this.item.id),
          1,
          r.data,
        )
        this.dialogLoading = false
        this.achieveDialog = false
        this.$store.commit("message", {
          text: this.item.name + " – достигнуто!",
        })
      })
    },
  },
}
</script>
