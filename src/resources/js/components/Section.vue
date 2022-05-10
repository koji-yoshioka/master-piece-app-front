<template>
  <section class="section">
    <slot></slot>
  </section>
</template>

<style lang="scss" scoped>
@use "~@/mixins";

.section {
  @include mixins.mq(sp) {
    padding-top: 40px;
    padding-bottom: 60px;
  }

  @include mixins.mq(tablet) {
    padding-top: 50px;
    padding-bottom: 70px;
  }

  @include mixins.mq(pc) {
    padding-top: 60px;
    padding-bottom: 80px;
  }
}
</style>
