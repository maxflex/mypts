<template>
  <div
    class="achievement__item"
    :class="{
      achievement__item_achieved: item.is_achieved,
    }"
  >
    <h1 class="secondary--text mb-1">
      <Pts :value="item.pts" />
    </h1>
    <div>
      {{ item.name }}
    </div>
  </div>
</template>

<script>
import Pts from "@/components/Pts"

export default {
  props: {
    item: {
      type: Object,
      required: true,
    },
  },

  components: { Pts },
}
</script>

<style lang="scss">
.achievement__item {
  border: 2px solid rgba(#f3377a, 0.5);
  border-radius: 8px;
  height: 120px;
  margin: 10px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  opacity: 0.3;
  text-align: center;
  padding: 20px;
  &_achieved {
    opacity: 1;
    color: white;
    background: #f3377a;
  }
}
</style>
