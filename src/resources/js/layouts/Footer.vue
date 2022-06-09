<template>
  <footer id="footer" class="l-footer">
    <ul class="l-footer__list">
      <li class="l-footer__item">
        <router-link to="/privacy-policy" exact>プライバシーポリシー</router-link>
      </li>
      <li class="l-footer__item">
        <router-link to="/security" exact>情報セキュリティ方針</router-link>
      </li>
      <li class="l-footer__item">
        <router-link to="/term" exact>利用規約</router-link>
      </li>
      <li class="l-footer__item">
        <router-link to="/contact" exact>お問い合わせ</router-link>
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
  z-index: 3001;

  @include mixins.mq(sp) {
    padding-left: 20px;
  }

  @include mixins.mq(tablet) {
    align-items: center;
    row-gap: 30px;
  }

  @include mixins.mq(pc) {
    align-items: center;
    row-gap: 30px;
  }
}

.l-footer__item {
  line-height: 1.8;
}

.l-footer__list {
  @include mixins.mq(sp) {}

  @include mixins.mq(tablet) {
    display: flex;
    flex-wrap: wrap;
  }

  @include mixins.mq(pc) {
    display: flex;
    flex-wrap: wrap;
  }
}

.l-footer__item {
  @include mixins.mq(sp) {
    width: 100%;
  }

  @include mixins.mq(tablet) {
    width: 50%;
  }

  @include mixins.mq(pc) {
    width: 50%;
  }

}
</style>
