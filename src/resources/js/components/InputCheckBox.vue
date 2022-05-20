<script setup lang="ts">
const props = defineProps({
  id: {
    type: String,
    default: null,
  },
  value: {
    type: String,
    default: null,
  },
  checked: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits<{
  (e: 'click', value: string): void
}>()

const click = (event: Event) => {
  emit('click', props.value)
}
</script>

<template>
  <div class="c-input-check-box">
    <input class="c-input-check-box__input" type="checkbox" :id="id" :value="value" :checked="checked"
      @click.stop="click" />
    <label class="c-input-check-box__label" :for="id">
      <slot></slot>
    </label>
  </div>
</template>

<style lang="scss" scoped>
.c-input-check-box {
  align-items: center;
  border: transparent 2px solid;
  column-gap: 5px;
  display: flex;
  width: fit-content;

  &:focus-within {
    border: #1967d2 2px solid;
  }
}
</style>
