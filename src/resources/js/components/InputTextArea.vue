<script setup lang="ts">
import { PropType, ref } from 'vue'
import { ErrorObject } from '@vuelidate/core'

const showError = ref(false)

const props = defineProps({
  rows: {
    type: Number,
    default: 5,
  },
  maxlength: {
    type: Number,
    default: null,
  },
  placeholder: {
    type: String,
    defautl: null,
  },
  errors: {
    type: Array as PropType<Array<ErrorObject>>,
    default: (): Array<ErrorObject> => [],
  },
})

const emit = defineEmits(['update:modelValue'])

const input = (event: Event) => {
  showError.value = false
  const target = event.target as HTMLInputElement;
  emit('update:modelValue', target.value)
}

const blur = (event: Event) => {
  // フォーカスが外れたタイミングでエラーを表示する
  showError.value = props.errors.length > 0
}

</script>

<template>
  <div class="c-input-text-area">
    <textarea class="c-input-text-area__input" :class="{ 'has-error': showError }" :rows="rows" :maxlength="maxlength"
      :placeholder="placeholder" @input="input" @blur="blur">{{ $attrs.modelValue }}</textarea>
    <template v-if="showError" v-for="error of errors" :key="error.$uid">
      <p class="c-input-text-area__error-msg">{{ error.$message }}</p>
    </template>
  </div>
</template>

<style lang="scss" scoped>
.c-input-text-area {
  width: 100%;
}

.c-input-text-area__input {
  background-color: #eee;
  border: #dcc090 2px solid;
  border-radius: 3px;
  // 16pxより小さいとiOSで自動でズームされてしまうので16pxで固定
  font-size: 16px;
  padding: 10px;
  width: 100%;

  &:focus {
    border: #1967d2 2px solid;
  }
}

.c-input-text-area__input.has-error {
  background-color: #ffd9d9;
  border: transparent 2px solid;
}

.c-input-text-area__error-msg {
  color: #ff0000;
  margin-top: 10px;
}
</style>

