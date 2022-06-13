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
// ログイン実行中フラグ
const loggingIn = ref<boolean>(false)
// ログアウト実行中フラグ
const loggingOut = ref<boolean>(false)
// ログアウト確認モーダル表示フラグ
const showLogoutConfirmModal = ref<boolean>(false)

// ログイン済フラグ
const isLoggingIn = computed(() => !!authStore.getters.loginUser)
// ログインユーザ画像
const loginUserImage = computed(() => {
  const defaultImage = 'https://static.master-piece.site/common/no-image.jpeg'
  if (!!authStore.getters.loginUser) {
    return authStore.getters.loginUser.imageFileName
      ? `https://static.master-piece.site/users/${authStore.getters.loginUser.id}/${authStore.getters.loginUser.imageFileName}`
      : defaultImage
  } else {
    return defaultImage
  }
})

// ログインユーザ名
const loginUserName = computed(() => (!!authStore.getters.loginUser) ? authStore.getters.loginUser.name : '')

// ログアウト確認モーダルを閉じる
const closeLogoutConfirmModal = () => {
  showLogoutConfirmModal.value = false
}

// トップページへ遷移
const toTop = () => {
  toggleHamburgerMenu.value = false
  router.push({ name: 'top' })
}

// 検索ページへ遷移
const toSearch = () => {
  toggleHamburgerMenu.value = false
  router.push({ name: 'search' })
}

// マイページへ遷移
const toMyPage = () => {
  toggleHamburgerMenu.value = false
  router.push({ name: 'my-page' })
}

// 会員登録ページへ遷移
const toSignUp = () => {
  toggleHamburgerMenu.value = false
  router.push({ name: 'sign-up' })
}

// ログインページへ遷移
const toLogin = () => {
  toggleHamburgerMenu.value = false
  router.push({ name: 'login' })
}

// ゲストユーザログイン
const guestLogin = async () => {
  loggingIn.value = true
  const userInfo = await httpService.guestLogin()
  loggingIn.value = false
  toggleHamburgerMenu.value = false
  authStore.dispatch('setUser', userInfo)
  if (userInfo) {
    router.push({ name: 'top' })
  }
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

    <div class="l-header__menu-group">
      <nav class="l-header__menu" :class="{ 'is-selected': toggleHamburgerMenu }">
        <ul class="l-header__menu-item-list">
          <li class="l-header__menu-item" @click.stop="toSearch">
            検索
          </li>
          <li class="l-header__menu-item" v-show="isLoggingIn" @click.stop="toMyPage">
            マイページ
          </li>
          <li class="l-header__menu-item" v-show="!isLoggingIn" @click.stop="toSignUp">
            会員登録
          </li>
          <li class="l-header__menu-item" v-show="!isLoggingIn" @click.stop="toLogin">
            ログイン
          </li>
          <li class="l-header__menu-item" v-show="!isLoggingIn" @click.stop="guestLogin">
            ゲストログイン
          </li>
          <li class="l-header__menu-item" v-show="isLoggingIn" @click.stop="showLogoutConfirmModal = true">
            ログアウト
          </li>
        </ul>
      </nav>
      <div class="l-header__avatar" v-show="isLoggingIn">
        <figure class="l-header__avatar-image-area">
          <img class="l-header__avatar-image" :src="loginUserImage" alt="avatar">
        </figure>
        <p class="l-header__avatar-title">{{ `${loginUserName}さん` }}</p>
      </div>

      <div class="l-header-hamburger-menu" :class="{ 'is-selected': toggleHamburgerMenu }"
        @click.stop="toggleHamburgerMenu = !toggleHamburgerMenu">
        <span class="l-header-hamburger-menu__bar"></span>
        <span class="l-header-hamburger-menu__bar"></span>
        <span class="l-header-hamburger-menu__bar"></span>
      </div>
    </div>
  </header>

  <div class="l-header__guest-login-loading-area">
    <vue-element-loading class="l-header__guest-login-loading" :active="loggingIn" :background-color="'#1c1c1c'"
      :color="'#fff'" :is-full-screen="true" :spinner="'spinner'" :text="'ログイン処理を実行しています'" />
  </div>

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

.l-header__menu-group {

  @include mixins.mq(sp) {
    align-items: center;
    column-gap: 20px;
    display: flex;
  }

  @include mixins.mq(tablet) {
    align-items: center;
    column-gap: 20px;
    display: flex;
  }

  @include mixins.mq(pc) {
    align-items: center;
    column-gap: 20px;
    display: flex;
  }
}

.l-header__avatar {
  align-items: center;
  display: flex;

  @include mixins.mq(sp) {
    column-gap: 5px;
  }

  @include mixins.mq(tablet) {
    column-gap: 10px;
  }

  @include mixins.mq(pc) {
    column-gap: 10px;
  }
}

.l-header__avatar-image-area {
  border: #dcc090 1px solid;
  border-radius: 50%;
  height: 100%;
  position: relative;

  @include mixins.mq(sp) {
    width: 30px;
  }

  @include mixins.mq(tablet) {
    width: 40px;
  }

  @include mixins.mq(pc) {
    width: 50px;
  }

  &::before {
    background-color: #fff;
    border-radius: 50%;
    content: "";
    display: block;
    padding-bottom: 100%;
  }
}

.l-header__avatar-image {
  border-radius: 50%;
  height: 100%;
  left: 0;
  position: absolute;
  top: 0;
  width: 100%;
}

.l-header__avatar-title {
  color: #ccc;
  display: -webkit-box;
  font-size: 12px;
  overflow: hidden;
  width: 100px;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 3;
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
    // justify-content: flex-end;
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

.l-header__guest-login-loading-area {
  &::v-deep(.velmld-overlay) {
    z-index: 9999;
  }
}

.l-header__guest-login-loading {
  opacity: 0.8;
}
</style>
