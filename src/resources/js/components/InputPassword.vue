<script setup lang="ts">
import { PropType, ref } from 'vue'
import { ErrorObject } from '@vuelidate/core'

const showError = ref(false)

const props = defineProps({
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
  <div class="c-input-password">
    <input class="c-input-password__input" :class="{ 'has-error': showError }" type="password"
      :placeholder="placeholder" :value="$attrs.modelValue" @input="input" @blur="blur" />
    <template v-if="showError" v-for="error of errors" :key="error.$uid">
      <p class="c-input-password__error-msg">{{ error.$message }}</p>
    </template>
  </div>
</template>

<style lang="scss" scoped>
.c-input-password {
  width: 100%;
}

.c-input-password__input {
  background-color: #eee;
  border: #dcc090 2px solid;
  border-radius: 3px;
  font: small-caption;
  font-size: 1.5em;
  height: 50px;
  letter-spacing: 0.25em;
  padding: 10px;
  width: 100%;

  &:focus {
    border: #1967d2 2px solid;
  }
}

.c-input-password__input.has-error {
  background-color: #ffd9d9;
  border: transparent 2px solid;
}

.c-input-password__error-msg {
  color: #ff0000;
  margin-top: 10px;
}
</style>

