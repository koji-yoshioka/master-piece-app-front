<script setup lang="ts">
import { PropType, ref } from 'vue'
import { ErrorObject } from '@vuelidate/core'

const showError = ref(false)

const props = defineProps({
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
  emit("update:modelValue", target.value)
}

const blur = (event: Event) => {
  // フォーカスが外れたタイミングでエラーを表示する
  showError.value = props.errors.length > 0
}
</script>

<template>
  <div class="c-input-number">
    <input class="c-input-number__input" :class="{ 'has-error': showError }" type="number" :maxlength="maxlength"
      :placeholder="placeholder" :value="$attrs.modelValue" @input="input" @blur="blur" />
    <template v-if="showError" v-for="error of errors" :key="error.$uid">
      <p class="c-input-number__error-msg">{{ error.$message }}</p>
    </template>
  </div>
</template>

<style lang="scss" scoped>
.c-input-number {
  width: 100%;
}

.c-input-number__input {
  background-color: #eee;
  border: #dcc090 2px solid;
  border-radius: 3px;
  height: 50px;
  padding: 10px;
  width: 100%;

  &:focus {
    border: #1967d2 2px solid;
  }
}

.c-input-number__input.has-error {
  background-color: #ffd9d9;
  border: transparent 2px solid;
}

.c-input-number__error-msg {
  color: #ff0000;
  margin-top: 10px;
}
</style>

