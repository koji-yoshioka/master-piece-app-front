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

  @include mixins.mq(tablet, pc) {
    padding-top: 60px;
    padding-bottom: 80px;
  }

  // @include mixins.mq(pc) {
  //   padding-top: 80px;
  //   padding-bottom: 120px;
  // }
}
</style>
