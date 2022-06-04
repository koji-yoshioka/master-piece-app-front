<script setup lang="ts">
import { computed, ref } from 'vue'
import { useStore as useAuthStore } from '@/store/auth'
import { httpService } from '@/services/httpService'
import { useRouter } from 'vue-router'
import ConfirmModal from '@/components/ConfirmModal.vue'

// 認証情報
const authStore = useAuthStore()
// ルーティング情報
const router = useRouter()

// ハンバーガーメニュー切替フラグ
const toggleHamburgerMenu = ref<boolean>(false)
// ログアウト実行中フラグ
const loggingOut = ref<boolean>(false)
// ログアウト確認モーダル表示フラグ
const showLogoutConfirmModal = ref<boolean>(false)

// ログイン済フラグ
const isLoggingIn = computed(() => !!authStore.getters.loginUser)

// ログアウト確認モーダルを閉じる
const closeLogoutConfirmModal = () => {
  showLogoutConfirmModal.value = false
}

// トップページへ遷移
const toTop = () => {
  toggleHamburgerMenu.value = false
  router.push({ name: 'top' })
}

// マイページへ遷移
const toMyPage = () => {
  toggleHamburgerMenu.value = false
  router.push({ name: 'my-page' })
}

// 検索ページへ遷移
const toSearch = () => {
  toggleHamburgerMenu.value = false
  router.push({ name: 'search' })
}

// ログインページへ遷移
const toLogin = () => {
  toggleHamburgerMenu.value = false
  router.push({ name: 'login' })
}

// 新規登録ページへ遷移
const toSignUp = () => {
  toggleHamburgerMenu.value = false
  router.push({ name: 'sign-up' })
}

// お問い合わせページへ遷移
const toContact = () => {
  toggleHamburgerMenu.value = false
  router.push({ name: 'contact' })
}

// ログアウト
const logout = async () => {
  toggleHamburgerMenu.value = false
  loggingOut.value = true
  const userInfo = await httpService.logout()
  authStore.dispatch('setUser', userInfo)
  loggingOut.value = false
  showLogoutConfirmModal.value = false
  router.push('/')
}
</script>

<template>
  <header id="header" class="l-header">
    <h1 class="l-header__logo" @click.stop="toTop">
      <figure class="l-header__logo-image-area">
        <img class="l-header__logo-image" src="/images/logo/logo.svg" alt="MASTER PIECE" />
      </figure>
    </h1>

    <div class="l-header-hamburger-menu" :class="{ 'is-selected': toggleHamburgerMenu }"
      @click.stop="toggleHamburgerMenu = !toggleHamburgerMenu">
      <span class="l-header-hamburger-menu__bar"></span>
      <span class="l-header-hamburger-menu__bar"></span>
      <span class="l-header-hamburger-menu__bar"></span>
    </div>

    <nav class="l-header__menu" :class="{ 'is-selected': toggleHamburgerMenu }">
      <ul class="l-header__menu-item-list">
        <li class="l-header__menu-item" v-show="isLoggingIn" @click.stop="toMyPage">
          マイページ
        </li>
        <li class="l-header__menu-item" @click.stop="toSearch">
          検索
        </li>
        <li class="l-header__menu-item" v-show="!isLoggingIn" @click.stop="toLogin">
          ログイン
        </li>
        <li class="l-header__menu-item" v-show="!isLoggingIn" @click.stop="toSignUp">
          新規登録
        </li>
        <li class="l-header__menu-item" v-show="isLoggingIn" @click.stop="showLogoutConfirmModal = true">
          ログアウト
        </li>
        <li class="l-header__menu-item" @click.stop="toContact">
          お問い合わせ
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

.l-header__logo {
  &:hover {
    cursor: pointer;
  }
}

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

.l-header-hamburger-menu {
  @include mixins.mq(sp) {
    display: flex;
    flex-direction: column;
    row-gap: 10px;
    width: 20px;
  }

  @include mixins.mq(tablet) {
    display: flex;
    flex-direction: column;
    row-gap: 10px;
    width: 20px;
  }

  @include mixins.mq(pc) {
    display: none;
  }
}

.l-header-hamburger-menu.is-selected {
  &>.l-header-hamburger-menu__bar {
    &:nth-of-type(1) {
      transform: translateY(12px) rotate(-45deg);
    }

    &:nth-of-type(2) {
      opacity: 0;
    }

    &:nth-of-type(3) {
      transform: translateY(-12px) rotate(45deg);
    }
  }
}

.l-header-hamburger-menu__bar {
  background-color: #dcc090;
  border-radius: 4px;
  height: 2px;
}

.l-header__menu {
  @include mixins.mq(sp) {
    position: absolute;
    right: -100vw;
    top: functions.getHeaderHeight(sp);
    transition: .5s;
    width: 100%;
  }

  @include mixins.mq(tablet) {
    position: absolute;
    right: -100vw;
    top: functions.getHeaderHeight(tablet);
    transition: .5s;
    width: 100%;
  }

  @include mixins.mq(pc) {}
}

.l-header__menu.is-selected {
  @include mixins.mq(sp) {
    background-color: #1c1c1c;
    height: 100vh;
    right: 0;
  }

  @include mixins.mq(tablet) {
    background-color: #1c1c1c;
    height: 100vh;
    right: 0;
  }

  @include mixins.mq(pc) {}
}

.l-header__menu-item-list {
  @include mixins.mq(sp) {}

  @include mixins.mq(tablet) {}

  @include mixins.mq(pc) {
    column-gap: 20px;
    display: flex;
    flex: 1;
    justify-content: flex-end;
  }
}

.l-header__menu-item {
  &:hover {
    cursor: pointer;
  }

  @include mixins.mq(sp) {
    align-items: center;
    border-bottom: 2px solid #333;
    display: flex;
    padding: 20px 10px;
  }

  @include mixins.mq(tablet) {
    align-items: center;
    border-bottom: 2px solid #333;
    display: flex;
    padding: 20px 10px;
  }

  @include mixins.mq(pc) {}
}
</style>
