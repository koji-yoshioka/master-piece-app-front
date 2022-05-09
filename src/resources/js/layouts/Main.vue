<script setup lang="ts">
</script>

<template>
  <main id="main">
    <router-view />
  </main>
</template>

<style lang="scss" scoped>
@use "~@/variables";
@use "~@/mixins";
@use "~@/functions";

#main {
  background-color: variables.$base-color;
  @include mixins.mq(sp) {
    padding-top: calc(functions.getHeaderHeight(sp));
  }
  @include mixins.mq(tablet) {
    padding-top: calc(functions.getHeaderHeight(tablet));
  }
  @include mixins.mq(pc) {
    padding-top: calc(functions.getHeaderHeight(pc));
  }
}
</style>
