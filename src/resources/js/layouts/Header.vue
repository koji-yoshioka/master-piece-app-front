<script setup lang="ts">
// import { defineComponent } from "vue";
// export default defineComponent({
//   setup() { },
// });
</script>

<template>
  <header id="header">
    <router-link to="/" exact>
      <h1>
        <figure class="logo">
          <img class="logo__img" src="/images/logo.svg" alt="MASTER PIECE" />
        </figure>
      </h1>
    </router-link>
    <nav class="header-menu">
      <ul class="header-menu__item-list">
        <!-- <li class="header-menu__item">
          <router-link :to="{ name: 'search', query: { prefectureId: 13 } }" exact>検索</router-link>
        </li>
        <li class="header-menu__item">
          <router-link to="/reserve" exact>予約</router-link>
        </li>

        <li class=" header-menu__item">
          <router-link to="/contact" exact>お問い合わせ</router-link>
        </li>
        <li class="header-menu__item">
          <router-link to="/user-login" exact>ログイン</router-link>
        </li>
        <li class="header-menu__item">
          <router-link to="/user-sign-up" exact>新規登録</router-link>
        </li> -->
      </ul>
    </nav>
  </header>
</template>

<style lang="scss" scoped>
@use "~@/variables";
@use "~@/mixins";
@use "~@/functions";

#header {
  align-items: center;
  background-color: variables.$base-color;
  border-bottom: 0.5px solid #dcc090;
  color: #dcc090;
  display: flex;
  justify-content: space-between;
  position: fixed;
  width: 100%;
  z-index: 9999;

  @include mixins.mq(sp) {
    height: functions.getHeaderHeight(sp);
    padding: 5px 10px;
  }

  @include mixins.mq(tablet) {
    height: functions.getHeaderHeight(tablet);
    padding: 10px 15px;
  }

  @include mixins.mq(pc) {
    height: functions.getHeaderHeight(pc);
    padding: 10px 20px;
  }
}

.logo {
  width: max-content;

  @include mixins.mq(sp) {
    &__img {
      width: 50px;
    }
  }

  @include mixins.mq(tablet) {
    &__img {
      width: 60px;
    }
  }

  @include mixins.mq(pc) {
    &__img {
      width: 80px;
    }
  }
}

.header-menu__item-list {
  display: flex;
  flex: 1;
  justify-content: flex-end;
}

.header-menu__item {
  margin-right: 20px;
}

.header-menu__item:last-child {
  margin-right: 0;
}
</style>
