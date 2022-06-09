<script setup lang="ts">
import { PropType } from 'vue'
const props = defineProps({
  accept: {
    type: Array as PropType<string[]>,
    default: ['image/*'],
  },
  preview: {
    type: String as PropType<string | ArrayBuffer | null>,
    default: null,
  },
});
const emit = defineEmits<{
  (e: 'upload', value: File): void
  (e: 'fileDelete'): void
}>()
const fileChange = (event: Event) => {
  let files = null
  if (event instanceof DragEvent) {
    // ドラッグ＆ドロップされた場合
    files = event.dataTransfer ? event.dataTransfer.files : null
  } else {
    // ファイル選択された場合
    const target = event.target as HTMLInputElement
    files = target.files ? target.files : null
  }
  if (!files || files.length === 0) {
    return
  }
  const targetImage = files[0]
  const reader = new FileReader()
  reader.readAsDataURL(targetImage)
  emit('upload', targetImage)
}
const click = (event: Event) => {
  // 1つ前の画像情報が残っている可能性があるため、初期化
  const target = event.target as HTMLInputElement
  target.value = ''
}
const fileDelete = (event: Event) => {
  emit('fileDelete')
}
</script>

<template>
  <div class="c-input-circle-image">
    <font-awesome-icon v-show="preview" :icon="['far', 'circle-xmark']" size="2x"
      class="c-input-circle-image__delete-icon" @click.stop="fileDelete" />
    <label class="c-input-circle-image__drag-drop-area" @dragenter.prevent @dragover.prevent @drop.prevent="fileChange">
      <input :accept="accept.join()" class="c-input-circle-image__file" type="file" @click="click"
        @change="fileChange" />
      <span class="c-input-circle-image__text">
        <slot v-if="!preview">未選択</slot>
      </span>
      <img v-show="preview" :src="preview" class="c-input-circle-image__preview" />
    </label>
  </div>
</template>

<style lang="scss" scoped>
@use "~@/variables";
@use "~@/mixins";

.c-input-circle-image {
  position: relative;
  width: 100%;
}

.c-input-circle-image__delete-icon {
  color: #ccc;
  position: absolute;
  right: 0;
  z-index: 1;

  &:hover {
    cursor: pointer;
  }
}

.c-input-circle-image__drag-drop-area {
  background-color: #fff;
  border: #dcc090 2px solid;
  border-radius: 50%;
  display: block;
  position: relative;
  width: 100%;

  &::before {
    border-radius: 50%;
    content: "";
    display: block;
    padding-bottom: 100%;
  }

  &:focus-within {
    border: #1967d2 2px solid;
  }

  &:hover {
    cursor: pointer;
  }
}

.c-input-circle-image__file {
  border-radius: 50%;
  left: 0;
  opacity: 0;
  position: absolute;
  top: 0;
  width: 100%;
}

.c-input-circle-image__text {
  color: #ccc;
  font-weight: 700;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.c-input-circle-image__preview {
  border-radius: 50%;
  height: 100%;
  left: 0;
  object-fit: cover;
  position: absolute;
  top: 0;
  width: 100%;
}
</style>
