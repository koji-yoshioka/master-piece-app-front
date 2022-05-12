<script setup lang="ts">
import { onMounted, ref } from 'vue'

const props = defineProps({
  title: {
    type: String,
    default: null,
  },
  show: {
    type: Boolean,
    default: false,
  },
})
const emit = defineEmits<{
  (e: 'close'): void
}>()

const bodyHeight = ref<number>(0)

const close = (event: Event) => {
  emit('close')
}

onMounted(() => {
  bodyHeight.value = document.body.scrollHeight
})

</script>

<template>
  <teleport to="body">
    <div v-if="show" class="c-modal">
      <div class="c-modal__background" :style="'height:' + bodyHeight + 'px'" @click.stop="close($event)">
        <div class="c-modal__body" @click.stop>

          <slot name="loading"></slot>

          <div class="c-modal__header">
            <span class="c-modal__header-title">{{ title }}</span>
            <font-awesome-icon class="c-modal__header-close" :icon="['fas', 'xmark']" size="2x"
              @click.stop="close($event)" />
          </div>
          <div class="c-modal__content">
            <slot name="content"></slot>
          </div>
          <div class="c-modal__footer">
            <slot name="footer"></slot>
          </div>
        </div>
      </div>
    </div>
  </teleport>
</template>

<style lang="scss" scoped>
@use "~@/mixins";

.c-modal {}

.c-modal__background {
  align-items: center;
  background-color: rgba(0, 0, 0, 0.5);
  bottom: 0;
  display: flex;
  flex-direction: column;
  justify-content: center;
  left: 0;
  position: absolute;
  right: 0;
  top: 0;
  z-index: 9999;
}

.c-modal__body {
  background-color: #eee;
  border: #1c1c1c 2px solid;
  display: flex;
  flex-direction: column;
  margin: auto;
  position: fixed;
  height: fit-content;
  inset: 0;
  width: fit-content;
}

.c-modal__header {
  align-items: center;
  background-color: #1c1c1c;
  border: #dcc090 1px solid;
  color: #dcc090;
  display: flex;
  justify-content: space-between;

  @include mixins.mq(sp) {
    padding: 5px;
  }

  @include mixins.mq(tablet) {
    padding: 7.5px;
  }

  @include mixins.mq(pc) {
    padding: 10px;
  }
}

.c-modal__header-title {
  font-weight: 700;
}

.c-modal__header-close {
  &:hover {
    cursor: pointer;
  }
}

.c-modal__content {
  flex-grow: 1;
  overflow: auto;
  padding: 10px;
}
</style>

