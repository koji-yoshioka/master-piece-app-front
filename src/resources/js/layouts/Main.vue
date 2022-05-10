<template>
  <main id="main" class="l-main">
    <router-view />
  </main>
</template>

<style lang="scss" scoped>
@use "~@/functions";
@use "~@/mixins";
@use "~@/variables";

.l-main {
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
