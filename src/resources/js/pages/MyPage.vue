<script setup lang="ts">
import { ref } from 'vue'
import { useStore as useAuthStore } from '@/store/auth'
import { useRouter } from 'vue-router'
import { flashMessage } from '@smartweb/vue-flash-message'
import Section from '@/components/Section.vue'
import ConfirmModal from '@/components/ConfirmModal.vue'
import { httpService } from '@/services/httpService'

// 認証情報
const authStore = useAuthStore()
// ルーティング情報
const router = useRouter()
// 退会実行中フラグ
const withdrawing = ref<boolean>(false)
// 退会確認モーダル表示フラグ
const showWithdrawConfirmModal = ref<boolean>(false)

// ゲストユーザか
const isGuest = () => !!authStore.getters.loginUser.isGuest

// 退会確認モーダルを開く
const openWithdrawConfirmModal = () => {
  if (isGuest()) {
    // ゲストユーザの場合、エラー
    flashMessage.show({
      type: 'warning',
      text: 'ゲストユーザは退会できません。',
    })
  } else {
    showWithdrawConfirmModal.value = true
  }
}

// 退会確認モーダルを閉じる
const closeWithdrawConfirmModal = () => {
  showWithdrawConfirmModal.value = false
}

// プロフィール編集ページへ遷移
const toProfile = () => {
  if (isGuest()) {
    // ゲストユーザの場合、エラー
    flashMessage.show({
      type: 'warning',
      text: 'ゲストユーザはプロフィール編集できません。',
    })
  } else {
    router.push({ name: 'profile' })
  }
}

// プロフィール編集ページへ遷移
const toPasswordChange = () => {
  if (isGuest()) {
    // ゲストユーザの場合、エラー
    flashMessage.show({
      type: 'warning',
      text: 'ゲストユーザはパスワード変更できません。',
    })
  } else {
    router.push({ name: 'password-change' })
  }
}

// 予約履歴ページへ遷移
const toReserveHistory = () => {
  router.push({ name: 'reserve-history' })
}

// お気に入り一覧ページへ遷移
const toLikeCompanyList = () => {
  router.push({ name: 'like-company-list' })
}

//　退会
const withdraw = async () => {
  withdrawing.value = true
  const isSuccess = await httpService.withdraw(Number(authStore.getters.loginUser.id))
  withdrawing.value = false
  if (isSuccess) {
    authStore.dispatch('setUser', null)
    router.push({ name: 'top' })
  }
}
</script>

<template>
  <div class="page-my-page">
    <Section class="page-my-page__section">
      <h2 class="page-my-page__title">マイページメニュー</h2>
      <ul class="page-my-page__menu-list">
        <li class="page-my-page__menu" @click.stop="toProfile">
          <div class="page-my-page__menu-content">
            <font-awesome-icon :icon="['far', 'address-card']" size="5x" />
            <p class="page-my-page__menu-title">プロフィール編集</p>
          </div>
        </li>
        <li class="page-my-page__menu" @click.stop="toPasswordChange">
          <div class="page-my-page__menu-content">
            <font-awesome-icon :icon="['fas', 'key']" size="5x" />
            <p class="page-my-page__menu-title">パスワード変更</p>
          </div>
        </li>
        <li class="page-my-page__menu" @click.stop="toReserveHistory">
          <div class="page-my-page__menu-content">
            <font-awesome-icon :icon="['fas', 'history']" size="5x" />
            <p class="page-my-page__menu-title">予約履歴</p>
          </div>
        </li>
        <li class="page-my-page__menu" @click.stop="toLikeCompanyList">
          <div class="page-my-page__menu-content">
            <font-awesome-icon :icon="['fas', 'heart-circle-check']" size="5x" />
            <p class="page-my-page__menu-title">お気に入り一覧</p>
          </div>
        </li>
        <li class="page-my-page__menu" @click.stop="openWithdrawConfirmModal">
          <div class="page-my-page__menu-content">
            <font-awesome-icon :icon="['fas', 'person-running']" size="5x" />
            <p class="page-my-page__menu-title">退会</p>
          </div>
        </li>
      </ul>
    </Section>
  </div>

  <ConfirmModal :title="'退会確認'" :show="showWithdrawConfirmModal" :executing="withdrawing" :loadingLabel="'退会処理を実行しています'"
    :executeBtnlabel="'はい'" :cancelBtnlabel="'いいえ'" @execute="withdraw" @cancel="closeWithdrawConfirmModal">
    退会しますか？
  </ConfirmModal>


</template>

<style lang="scss" scoped>
@use "~@/mixins";

.page-my-page {
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

.page-my-page__section {
  display: flex;
  flex-direction: column;
  margin: 0 auto;
  width: fit-content;

  @include mixins.mq(sp) {
    row-gap: 30px;
  }

  @include mixins.mq(tablet) {
    row-gap: 30px;
  }

  @include mixins.mq(pc) {
    max-width: 860px;
    row-gap: 40px;
    width: 100%;
  }
}

.page-my-page__title {
  color: #dcc090;
  font-size: 1.5em;
}

.page-my-page__menu-list {
  display: grid;

  @include mixins.mq(sp) {
    gap: 20px;
    grid-template-columns: repeat(2, 110px);
    justify-content: center;
  }

  @include mixins.mq(tablet) {
    gap: 50px;
    grid-template-columns: repeat(2, 160px);
    justify-content: center;
  }

  @include mixins.mq(pc) {
    grid-template-columns: repeat(4, 160px);
    justify-content: space-between;
    row-gap: 50px;
  }
}

.page-my-page__menu {
  background-color: #1c1c1c;
  border: 2px solid #dcc090;
  border-radius: 10px;
  color: #d7d5d3;
  cursor: pointer;
  padding: 10px;
  position: relative;

  @include mixins.mq(sp) {}

  @include mixins.mq(tablet) {}

  @include mixins.mq(pc) {}
}

.page-my-page__menu::before {
  content: "";
  display: block;
  padding-bottom: 100%;
}

.page-my-page__menu-content {
  align-items: center;
  display: flex;
  flex-direction: column;
  left: 50%;
  position: absolute;
  row-gap: 20px;
  top: 50%;
  transform: translate(-50%, -50%);
  width: 100%;
  -webkit-transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
}
</style>
