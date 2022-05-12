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
.c-label {
  width: fit-content;
}

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
</style>
