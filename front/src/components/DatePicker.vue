<template>
  <v-menu
    ref="datepicker"
    :close-on-content-click="false"
    :return-value.sync="date"
    transition="scale-transition"
    offset-y
    min-width="290px"
    v-model="menu"
  >
    <template v-slot:activator="{ on }">
      <v-text-field
        v-on="on"
        :error-messages="errorMessages"
        :hide-details="errorMessages === undefined"
        slot="activator"
        v-model="dateFormatted"
        @keyup="handleManualInput"
        :readonly="readonly"
        :disabled="disabled"
        :label="label"
        v-mask="'##.##.####'"
        :flat="flat"
        :dense="dense"
        :solo="solo"
        :background-color="backgroundColor"
        :append-icon="now ? 'mdi-clock-outline' : null"
        @click:append="setNow()"
      ></v-text-field>
    </template>
    <v-date-picker
      no-title
      locale="ru"
      :readonly="readonly"
      v-model="date"
      :first-day-of-week="1"
      :allowedDates="allowedDates"
      @input="$refs.datepicker.save(date)"
    >
    </v-date-picker>
  </v-menu>
</template>

<script>
export default {
  props: {
    value: {},
    label: {},
    flat: Boolean,
    dense: Boolean,
    solo: Boolean,
    backgroundColor: undefined,
    to: {
      type: String,
      default: null,
      required: false,
    },
    from: {
      type: String,
      default: null,
      required: false,
    },
    readonly: Boolean,
    disabled: Boolean,
    now: Boolean,
    errorMessages: {
      default: undefined,
    },
  },

  data() {
    return {
      menu: false,
      date: null,
      dateFormatted: null,
    }
  },

  created() {
    this.date = this.value
    this.dateFormatted = this.formatDate()
  },

  methods: {
    allowedDates(date) {
      if (this.from && this.to) {
        return (
          this.$moment(this.from).isSameOrBefore(date) &&
          this.$moment(date).isSameOrBefore(this.to)
        )
      }
      if (this.from) {
        return this.$moment(this.from).isSameOrBefore(date)
      }
      if (this.to) {
        return this.$moment(date).isSameOrBefore(this.to)
      }
      return true
    },

    formatDate() {
      if (!this.date) {
        return null
      }
      return this.$moment(this.date).format("DD.MM.YYYY")
    },

    parseDateFormatted() {
      const [day, month, year] = this.dateFormatted.split(".")
      return this.$moment([year, month, day].join("-")).format("YYYY-MM-DD")
    },

    handleManualInput() {
      if (this.dateFormatted.length > 0) {
        this.menu = false
        if (this.dateFormatted.length === 10) {
          this.$emit("input", this.parseDateFormatted())
        } else {
          this.$emit("invalid", this)
        }
      } else {
        this.menu = true
        this.$emit("input", null)
      }
    },

    setNow() {
      this.date = this.$moment().format("YYYY-MM-DD")
      this.$emit("now")
    },
  },

  watch: {
    date(val) {
      this.dateFormatted = this.formatDate()
      if (val !== undefined) {
        this.$emit("input", this.date)
      }
    },
    value(v) {
      this.date = v
      this.dateFormatted = this.formatDate()
    },
  },
}
</script>
