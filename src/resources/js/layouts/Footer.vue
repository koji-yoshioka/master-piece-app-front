<template>
  <footer id="footer" class="l-footer">
    <ul class="l-footer__list">
      <li class="l-footer__item">
        <a class="l-footer__link">サイトポリシー</a>
      </li>
      <li class="l-footer__item">
        <a class="l-footer__link">関連団体</a>
      </li>
      <li class="l-footer__item">
        <a class="l-footer__link">情報セキュリティ基本方針</a>
      </li>
      <li class="l-footer__item">
        <a class="l-footer__link">個人情報保護方針</a>
      </li>
      <li class="l-footer__item">
        <a class="l-footer__link">サイトマップ</a>
      </li>
    </ul>
    <p>Copyright © MASTER PIECE. All Rights Reserved</p>
  </footer>
</template>

<style lang="scss" scoped>
@use "~@/mixins";
@use "~@/variables";

.l-footer {
  background-color: variables.$base-color;
  border-top: 0.5px solid #dcc090;
  color: #ccc;
  display: flex;
  flex-direction: column;
  padding-top: 10px;
  padding-bottom: 10px;
  position: sticky;
  top: 100vh;
  width: 100%;
  z-index: 9999;

  @include mixins.mq(sp) {
    padding-left: 20px;
  }

  @include mixins.mq(tablet) {}

  @include mixins.mq(pc) {
    align-items: center;
    row-gap: 30px;
  }
}

.l-footer__item {
  line-height: 1.8;
}

.l-footer__list {
  @include mixins.mq(pc) {
    display: flex;
    flex-wrap: wrap;
  }
}

.l-footer__item {
  width: 50%;
}
</style>
