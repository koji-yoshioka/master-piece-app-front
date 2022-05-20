<script setup lang="ts">
import { ref } from 'vue'
import { getFullHeight } from '@/util'

const props = defineProps({
  title: {
    type: String,
    default: null,
  },
  value: {
    type: String,
    default: null,
  },
  loadingLabel: {
    type: String,
    default: null,
  },
  executeBtnlabel: {
    type: String,
    default: null,
  },
  cancelBtnlabel: {
    type: String,
    default: null,
  },
  show: {
    type: Boolean,
    default: false,
  },
  executing: {
    type: Boolean,
    default: true,
  },
})
const emit = defineEmits<{
  (e: 'execute', value: string): void;
  (e: 'cancel'): void;
}>()

// const executing = ref<boolean>(false)

const execute = (event: Event) => {
  // executing.value = true
  emit('execute', props.value)
}
const cancel = (event: Event) => {
  emit('cancel')
}
</script>

<template>
  <teleport to="body">
    <div v-if="show" class="c-confirm-modal">
      <div class="c-confirm-modal__background" :style="'height:' + getFullHeight() + 'px'" @click.stop="cancel">
        <div class="c-confirm-modal__body" @click.stop>
          <div v-if="loadingLabel" class="c-confirm-modal__loading-area">
            <vue-element-loading class="c-confirm-modal__loading" :active="executing" :background-color="'#1c1c1c'"
              :color="'#fff'" :is-full-screen="true" :spinner="'spinner'" :text="loadingLabel" />
          </div>
          <div class="c-confirm-modal__header">
            <span class="c-confirm-modal__header-title">{{ title }}</span>
            <font-awesome-icon class="c-confirm-modal__header-close" :icon="['fas', 'xmark']" size="2x"
              @click.stop="cancel" />
          </div>
          <div class="c-confirm-modal__text">
            <slot></slot>
          </div>
          <div class="c-confirm-modal__footer">
            <button class="c-confirm-modal__footer-button is-left" @click.stop="execute">{{ executeBtnlabel }}</button>
            <button class="c-confirm-modal__footer-button is-right" @click.stop="cancel">{{ cancelBtnlabel }}</button>
          </div>
        </div>
      </div>
    </div>
  </teleport>
</template>

<style lang="scss" scoped>
@use "~@/mixins";

.c-confirm-modal {}

.c-confirm-modal__background {
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

.c-confirm-modal__body {
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

.c-confirm-modal__loading-area {}

.c-confirm-modal__loading {
  opacity: 0.8;
}

.c-confirm-modal__header {
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

.c-confirm-modal__header-title {
  font-weight: 700;
}

.c-confirm-modal__header-close {
  &:hover {
    cursor: pointer;
  }
}

.c-confirm-modal__text {
  align-items: center;
  display: flex;
  font-size: 1.2em;
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

.c-confirm-modal__footer {
  display: flex;
  justify-content: center;
  padding: 10px;

  @include mixins.mq(sp) {
    gap: 15px;
  }

  @include mixins.mq(tablet) {
    gap: 20px
  }

  @include mixins.mq(pc) {
    gap: 30px
  }
}

.c-confirm-modal__footer-button {
  border: transparent 2px solid;
  border-radius: 3px;
  width: 120px;
  padding: 10px;
  text-align: center;

  &:focus {
    border: #1967d2 2px solid;
  }
}

.c-confirm-modal__footer-button.is-left {
  background-color: #03A768;
  color: #fff;
}

.c-confirm-modal__footer-button.is-right {
  background-color: #fff;
  color: #8b8484;
}
</style>

