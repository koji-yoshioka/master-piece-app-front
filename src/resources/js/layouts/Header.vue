<script setup lang="ts">
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useStore } from "@/store/store"
import ConfirmModal from '@/components/ConfirmModal.vue'

const router = useRouter()
const store = useStore()
const isLoggingIn = computed(() => store.getters.isLoggingIn)

// ログアウト実行中フラグ
const loggingOut = ref<boolean>(false)

// ログアウト確認モーダル表示フラグ
const showLogoutConfirmModal = ref<boolean>(false)

// ログアウト確認モーダルを閉じる
const closeLogoutConfirmModal = () => {
  showLogoutConfirmModal.value = false
}

const logout = async () => {
  loggingOut.value = true
  await store.dispatch('logout')
  loggingOut.value = false
  showLogoutConfirmModal.value = false
  router.push('/')
}
</script>

<template>
  <header id="header" class="l-header">
    <router-link class="l-header__logo" to="/" exact>
      <h1 class="l-header__logo-heading">
        <figure class="l-header__logo-image-area">
          <img class="l-header__logo-image" src="/images/logo/logo.svg" alt="MASTER PIECE" />
        </figure>
      </h1>
    </router-link>
    <nav class="l-header__menu">
      <ul class="l-header__menu-item-list">

        <li class="l-header__menu-item">
          <router-link :to="'/my-page'" exact>マイページ</router-link>
        </li>

        <li class="l-header__menu-item">
          <router-link :to="'/search'" exact>検索</router-link>
        </li>
        <li class="l-header__menu-item" v-show="!isLoggingIn">
          <router-link to="/login" exact>ログイン</router-link>
        </li>
        <li class="l-header__menu-item" v-show="!isLoggingIn">
          <router-link to="/sign-up" exact>新規登録</router-link>
        </li>
        <li class="l-header__menu-item" v-show="isLoggingIn">
          <p class="l-header__menu-item-link" @click.stop="showLogoutConfirmModal = true">ログアウト</p>
        </li>
        <li class="l-header__menu-item">
          <router-link to="/contact" exact>お問い合わせ</router-link>
        </li>
      </ul>
    </nav>
  </header>

  <ConfirmModal :title="'ログアウト確認'" :show="showLogoutConfirmModal" :executing="loggingOut"
    :loadingLabel="'ログアウト処理を実行しています'" :executeBtnlabel="'ログアウト'" :cancelBtnlabel="'キャンセル'" @execute="logout"
    @cancel="closeLogoutConfirmModal">
    ログアウトしますか？
  </ConfirmModal>

</template>

<style lang="scss" scoped>
@use "~@/functions";
@use "~@/mixins";

.l-header {
  align-items: center;
  background-color: #1c1c1c;
  border-bottom: 0.5px solid #dcc090;
  color: #dcc090;
  display: flex;
  justify-content: space-between;
  position: fixed;
  width: 100%;
  z-index: 3001;

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

.l-header__logo {}

.l-header__logo-heading {}

.l-header__logo-image-area {
  width: max-content;
}

.l-header__logo-image {
  @include mixins.mq(sp) {
    width: 50px;
  }

  @include mixins.mq(tablet) {
    width: 60px;
  }

  @include mixins.mq(pc) {
    width: 80px;
  }
}

.l-header__menu {}

.l-header__menu-item-list {
  column-gap: 20px;
  display: flex;
  flex: 1;
  justify-content: flex-end;
}

.l-header__menu-item {}

.l-header__menu-item-link {
  &:hover {
    cursor: pointer;
  }
}
</style>
