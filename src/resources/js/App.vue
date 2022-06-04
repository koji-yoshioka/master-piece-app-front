<script setup lang="ts">
import Header from "@/layouts/Header.vue"
import Main from "@/layouts/Main.vue"
import Footer from "@/layouts/Footer.vue"
</script>

<template>
  <FlashMessage class="flash-message" position="left top" :time="5000" />
  <Header></Header>
  <Main></Main>
  <Footer></Footer>
</template>

<style lang="scss" scoped>
.flash-message {
  &::v-deep(._vue-flash-msg-body) {
    left: 0;
    padding: 20px;
    width: 100%;
    // ヘッダのz-indexより大きくする
    z-index: 3002;
  }
}
</style>
