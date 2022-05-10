<script setup lang="ts">
import { PropType, ref } from 'vue'

const props = defineProps({
  id: {
    type: String as PropType<string>,
    default: '',
  },
  required: {
    type: Boolean,
    default: false,
  },
});
</script>

<template>
  <div class="c-label">
    <label v-if="required" class="c-label--required"></label>
    <label class="c-label__text">
      <slot></slot>
    </label>
  </div>
</template>

<style lang="scss" scoped>
@use "~@/variables";

.c-label {}

.c-label--required {
  &::before {
    content: "*";
    color: #ff0000;
    margin-right: 4px;
  }
}

.c-label__text {
  color: #dcc090;
  display: inline-block;
  margin-bottom: 10px;
}

// &__text {
//   color: variables.$font-color;
//   display: inline-block;
//   margin-bottom: 10px;
// }

// &__text--required {
//   background-color: #ff0000;
//   border-radius: 10px;
//   color: #fff;
//   display: inline-block;
//   padding: 5px 10px;
//   margin-left: 10px;
// }
</style>
