<template>
  <v-card outlined class="full-width">
    <v-card-text>
      <v-row>
        <v-col cols="12">
          <v-menu offset-y :value="items.length > 0">
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
                v-for="item in items"
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
      <v-btn rounded class="px-5" color="accent" :loading="adding" @click="add">
        Добавить
      </v-btn>
      <v-spacer />
    </v-card-actions>
  </v-card>
</template>

<script>
const apiUrl = "entries"
import Pts from "@/components/Pts"
import { debounce } from "lodash"

export default {
  components: { Pts },

  data() {
    return {
      item: {
        name: null,
        pts: null,
      },
      items: [],
      isLoading: false,
      adding: false,
    }
  },

  created() {
    this.search = debounce(() => {
      if (this.item.comment.length < 3) {
        this.items = []
        return
      }
      this.isLoading = true
      const params = {
        comment: this.item.comment,
      }
      this.$http
        .get(apiUrl, { params })
        .then(r => {
          this.items = r.data.data
        })
        .finally(() => (this.isLoading = false))
    }, 300)
  },

  methods: {
    selectAutocomplete(item) {
      this.item = item
      this.items = []
    },

    add() {
      this.adding = true
      this.$http
        .post(apiUrl, this.item)
        .then(() => {
          this.$store.commit("message", { text: "добавлено" })
          this.$router.push({ name: "PageIndex" })
        })
        .finally(() => (this.adding = false))
    },
  },
}
</script>
