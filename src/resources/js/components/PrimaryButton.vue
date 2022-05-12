<script setup lang="ts">
const emit = defineEmits(['click'])
const onClick = () => {
  emit('click')
}
</script>

<template>
  <button class="c-primary-button" @click="onClick">
    <slot></slot>
  </button>
</template>

<style lang="scss" scoped>
.c-primary-button {
  background-color: #dcc090;
  border: 2px solid #1c1c1c;
  border-radius: 10px;
  color: #1c1c1c;
  height: 50px;
  letter-spacing: 0.1em;
  padding: 10px;
  text-align: center;
  transition: all 0.4s ease;
  width: max-content;

  &:focus {
    border: #1967d2 2px solid;
  }

  &:hover:not(:disabled) {
    background-color: #1c1c1c;
    border: 2px solid #dcc090;
    color: #dcc090;
    // transform: translateY(-2px);
  }

  &:disabled {
    background-color: #a9a9a9;
    color: #fff;
    cursor: not-allowed;
  }
}
</style>
