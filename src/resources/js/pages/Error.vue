<script setup lang="ts">
import Section from '@/components/Section.vue'
</script>

<template>
  <div class="page-error">
    <Section class="page-error__error-message-area">
      <h2 class="page-error__title">ページが見つかりませんでした。</h2>
      <div class="page-error__text-area">
        <p>申し訳ございませんが、お探しのページが見つかりませんでした。</p>
        <p>お探しのページは削除されたか、URLが変更された可能性がございます。</p>
      </div>
    </Section>
  </div>
</template>

<style lang="scss" scoped>
@use "~@/mixins";

.page-error {
  @include mixins.mq(sp) {
    padding-left: 20px;
    padding-right: 20px;
  }

  @include mixins.mq(tablet) {
    padding-left: 60px;
    padding-right: 60px;
  }

  @include mixins.mq(pc) {
    padding-left: 80px;
    padding-right: 80px;
  }
}

.page-error__error-message-area {
  align-items: center;
  display: flex;
  flex-direction: column;
  margin: 0 auto;
  width: 100%;

  @include mixins.mq(sp) {
    row-gap: 30px;
  }

  @include mixins.mq(tablet) {
    max-width: 860px;
    row-gap: 30px;
  }

  @include mixins.mq(pc) {
    max-width: 860px;
    row-gap: 40px;
  }
}

.page-error__title {
  color: #878990;
  font-size: 1.5rem;
}

.page-error__text-area {
  color: #878990;
  line-height: 1.5;
}
</style>
