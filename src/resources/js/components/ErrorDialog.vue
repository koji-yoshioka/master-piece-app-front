<script setup lang="ts">
import {
  Teleport as teleport_,
  TeleportProps,
  VNodeProps
} from 'vue'
import { getFullHeight } from '@/util'

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
})
const emit = defineEmits<{
  (e: 'proceed'): void;
}>()

const proceed = (event: Event) => {
  emit('proceed')
}

const Teleport = teleport_ as {
  new(): {
    $props: VNodeProps & TeleportProps
  }
}

</script>

<template>
  <component :is="Teleport" to="body">
    <div v-if="show" class="c-error-dialog">
      <div class="c-error-dialog__background" :style="'height:' + getFullHeight() + 'px'">
        <div class="c-error-dialog__body" @click.stop>
          <div class="c-error-dialog__header">
            <span class="c-error-dialog__header-title">エラー</span>
          </div>
          <div class="c-error-dialog__text">
            <slot></slot>
          </div>
          <div class="c-error-dialog__footer">
            <button class="c-error-dialog__footer-button" @click.stop="proceed">OK</button>
          </div>
        </div>
      </div>
    </div>
  </component>
</template>

<style lang="scss" scoped>
@use "~@/mixins";

.c-error-dialog {}

.c-error-dialog__background {
  align-items: flex-start;
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

.c-error-dialog__body {
  background-color: #eee;
  border: #1c1c1c 2px solid;
  display: flex;
  flex-direction: column;
  margin: auto;
  position: fixed;
  height: fit-content;
  inset: 0;

  @include mixins.mq(sp) {
    width: fit-content;
  }

  @include mixins.mq(tablet) {
    width: 400px;
  }

  @include mixins.mq(pc) {
    width: 400px;
  }
}

// .c-error-dialog__loading-area {}

// .c-error-dialog__loading {
//   opacity: 0.8;
// }

.c-error-dialog__header {
  align-items: center;
  background-color: #1c1c1c;
  border: #dcc090 1px solid;
  color: #dcc090;
  display: flex;
  justify-content: flex-start;

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

.c-error-dialog__header-title {
  font-weight: 700;
}

.c-error-dialog__text {
  align-items: center;
  display: flex;
  flex-direction: column;
  font-size: 1.2em;
  row-gap: 20px;
  justify-content: center;
  padding: 10px;

  @include mixins.mq(sp) {
    min-height: 50px;
  }

  @include mixins.mq(tablet) {
    min-height: 100px;
  }

  @include mixins.mq(pc) {
    min-height: 100px;
  }
}

.c-error-dialog__footer {
  display: flex;
  justify-content: center;
  padding: 10px;
}

.c-error-dialog__footer-button {
  border: transparent 2px solid;
  border-radius: 3px;
  width: 120px;
  padding: 10px;
  text-align: center;

  &:focus {
    border: #1967d2 2px solid;
  }
}

.c-error-dialog__footer-button {
  background-color: #d93025;
  color: #fff;
}
</style>

