<template>
  <v-container fill-height class="justify-space-between flex-column pa-0">
    <div
      class="flex-items justify-space-between full-width pa-3 plans-controls"
    >
      <div style="flex: 1;"></div>
      <div style="flex: 1; white-space: nowrap; text-align: center;">
        <v-chip
          :pill="mode === modes.minus"
          :outlined="mode !== modes.minus"
          class="mx-2"
          @click="mode = modes.minus"
          :class="{ accent: mode === modes.minus }"
        >
          минус
        </v-chip>
        <v-chip
          :pill="mode === modes.food"
          :outlined="mode !== modes.food"
          class="mx-2"
          @click="mode = modes.food"
          :class="{ accent: mode === modes.food }"
        >
          еда
        </v-chip>
        <v-chip
          :pill="mode === modes.plus"
          :outlined="mode !== modes.plus"
          @click="mode = modes.plus"
          :class="{ accent: mode === modes.plus }"
          class="mx-2"
        >
          плюс
        </v-chip>
      </div>
      <div style="flex: 1;" class="text-right">
        <v-btn icon color="accent" @click="addDialog()">
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </div>
    </div>
    <div class="full-width flex-grow-1 d-flex">
      <div v-if="loading" class="align-self-center full-width text-center">
        <Loader />
      </div>
      <div v-else class="mt-2 full-width">
        <v-simple-table>
          <tbody>
            <tr v-for="rule in currentItems" :key="rule.id" @click="view(rule)">
              <td>
                {{ rule.comment }}
              </td>
              <td class="text-right">
                <Pts :value="rule.pts" />
              </td>
            </tr>
          </tbody>
        </v-simple-table>
      </div>
    </div>
    <v-dialog v-model="dialog">
      <v-card outlined class="full-width">
        <v-card-text>
          <div class="d-flex flex-column">
            <div>
              <v-text-field
                hide-details
                v-model="item.comment"
                placeholder="комментарий"
              />
            </div>
            <div>
              <v-text-field
                hide-details
                placeholder="pts"
                v-model="item.pts"
              ></v-text-field>
            </div>
            <v-textarea
              hide-details
              rows="3"
              v-model="item.desc"
              placeholder="описание"
            />
            <div>
              <v-checkbox
                color="accent"
                v-model="item.is_food"
                label="Еда"
              ></v-checkbox>
            </div>
          </div>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn
            depressed
            class="px-5"
            color="accent"
            :loading="dialogLoading"
            @click="storeOrUpdate"
          >
            {{ item.id ? "Редактировать" : "Добавить" }}
          </v-btn>
          <v-spacer />
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="applyDialog">
      <v-card outlined>
        <v-card-title class="justify-center">
          <Pts class="headline" :value="dialogItem.pts" />
          <v-btn
            style="position: absolute; right: 4px; top: 4px;"
            x-small
            icon
            @click="
              applyDialog = false
              open(dialogItem)
            "
            color="accent"
          >
            <v-icon>
              mdi-pencil
            </v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text>
          <p class="body-1">
            {{ dialogItem.comment }}
          </p>
          <p
            v-if="dialogItem.desc"
            class="body-2 grey--text"
            v-html="dialogItem.desc_html"
          ></p>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn
            depressed
            class="px-5"
            color="accent"
            :loading="dialogLoading"
            @click="applyRule"
          >
            Применить
          </v-btn>
          <v-spacer />
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
const apiUrl = "rules"
import Loader from "@/components/Loader"
import Pts from "@/components/Pts"
import { cloneDeep, sortBy } from "lodash"

export default {
  components: { Loader, Pts },

  data() {
    const modes = {
      minus: "minus",
      plus: "plus",
      food: "food",
    }
    return {
      apiUrl,
      modes,
      mode: modes.minus,
      items: [],
      dialog: false,
      applyDialog: false,
      item: {},
      dialogItem: {},
      dialogLoading: false,
      loading: true,
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    loadData() {
      this.loading = true
      this.$http.get(apiUrl).then((r) => {
        this.items = r.data
        this.loading = false
      })
    },

    applyRule() {
      this.dialogLoading = true
      this.$http
        .post([apiUrl, "apply", this.dialogItem.id].join("/"))
        .then(() => {
          this.$store.commit("message", {
            text: "Применено",
          })
          this.$router.push({ name: "PageIndex" })
        })
        .finally(() => {
          this.dialogLoading = false
          this.applyDialog = false
        })
    },

    view(rule) {
      this.dialogItem = rule
      this.applyDialog = true
    },

    addDialog() {
      this.item = {}
      this.dialog = true
    },

    async storeOrUpdate() {
      this.dialogLoading = true
      if (this.item.id) {
        await this.$http
          .put([apiUrl, this.item.id].join("/"), this.item)
          .then((r) => {
            this.items.splice()
            this.items.splice(
              this.items.findIndex((e) => e.id === this.item.id),
              1,
              r.data,
            )
          })
      } else {
        await this.$http
          .post(apiUrl, this.item)
          .then((r) => this.items.unshift(r.data))
      }
      this.dialogLoading = false
      this.dialog = false
    },

    open(item) {
      this.item = cloneDeep(item)
      this.dialog = true
    },
  },

  computed: {
    currentItems() {
      let items = this.items.filter((e) => {
        if (this.mode === this.modes.food) {
          return e.is_food
        } else if (this.mode === this.modes.plus) {
          return e.pts >= 0 && !e.is_food
        }
        return e.pts < 0 && !e.is_food
      })
      items = sortBy(items, "pts")
      return this.mode === this.modes.plus ? items : items.reverse()
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
