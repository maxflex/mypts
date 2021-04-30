<script>
import { Line } from "vue-chartjs"

const lineOptions = {
  showLabel: false,
  backgroundColor: "transparent",
  borderWidth: 2,
  data: [],
  lineTension: 0.2,
  pointRadius: 0,
}

export default {
  extends: Line,
  props: {
    data: {
      type: Array,
      required: true,
    },
  },
  mounted() {
    this.renderChart(
      {
        labels: this.data
          .map(e => this.$moment(e.date).format("DD.MM"))
          .reverse(),
        datasets: [
          {
            ...lineOptions,
            label: "Плюс",
            borderColor: "#4CAF50",
            data: this.data.map(e => e.pts_plus_total).reverse(),
            hidden: true,
          },
          {
            ...lineOptions,
            label: "Минус",
            borderColor: "#666666",
            data: this.data
              .map(e => parseInt(e.pts_minus_total) * -1)
              .reverse(),
            hidden: true,
          },
          {
            ...lineOptions,
            label: "Всего",
            borderColor: "#f3377a",
            data: this.data.map(e => e.pts_total).reverse(),
          },
        ],
      },
      {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          labels: {
            usePointStyle: true,
            padding: 30,
          },
        },
      },
    )
  },
}
</script>
