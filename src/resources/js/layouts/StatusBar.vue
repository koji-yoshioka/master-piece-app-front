<script setup lang="ts">
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useStore } from "@/store/store"
import { OK } from '@/util'

const router = useRouter()
const store = useStore()
const isLoggingIn = computed(() => store.getters.isLoggingIn)

const show = ref<boolean>(false)

const isSuccess = ref<boolean>(false)
const isError = ref<boolean>(true)



const showStatusBar = computed(() => store.getters.showStatusBar)

</script>

<template>
  <div id="status-bar" class="l-status-bar" :class="{ 'is-error': isError, 'is-success': isSuccess }">
    <p class="l-status-bar__status">4XX　エラー</p>
  </div>
</template>

<style lang="scss" scoped>
@use "~@/functions";
@use "~@/mixins";

.l-status-bar {
  align-items: center;
  display: flex;
  justify-content: center;
  position: fixed;
  width: 100%;
  z-index: 9999;

  @include mixins.mq(sp) {
    height: calc(functions.getHeaderHeight(sp));
  }

  @include mixins.mq(tablet) {
    height: calc(functions.getHeaderHeight(tablet) / 2);
  }

  @include mixins.mq(pc) {
    height: calc(functions.getHeaderHeight(pc) / 2);
  }
}

.l-status-bar.is-error {
  background-color: #eba7a7;
  color: #e33939;
}
</style>
