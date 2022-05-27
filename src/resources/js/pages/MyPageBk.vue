<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useStore } from "@/store/store"
import axios, { AxiosResponse } from 'axios'
import { Company } from '@/typings/interfaces/search'
import { OK } from '@/util'
import Section from '@/components/Section.vue'
import SearchResult from '@/components/SearchResult.vue'
import ConfirmModal from '@/components/ConfirmModal.vue'

// グローバル情報
const store = useStore()
// ルーティング情報
const router = useRouter()

// ログイン確認モーダル表示フラグ
const showLoginConfirmModal = ref<boolean>(false)

// ログインページへ遷移する
const toLoginPage = () => {
  router.push({ name: 'login' })
}

// ログイン確認モーダルを閉じる
const closeLoginConfirmModal = () => {
  showLoginConfirmModal.value = false
}

// --start ページング関連
const currentPageNumber = ref<number>(1)
const perPage = ref<number>(2)
const getCompanies = computed(() => {
  let current = currentPageNumber.value * perPage.value
  let start = current - perPage.value;
  return companies.value.slice(start, current);
})
const updateHandler = (pageNumber: number) => {
  currentPageNumber.value = pageNumber
}
const getTotalPageCount = computed(() => Math.ceil(companies.value.length) / perPage.value)
// --end


// メニューリストページへ遷移する
const toMenuListPage = (companyId: number) => {
  if (isLoggingIn) {
    router.push({ name: 'menu-list', query: { companyId } })
  } else {
    showLoginConfirmModal.value = true
  }
}


// 企業情報取得済フラグ
const companiesLoaded = ref<boolean>(false)
// 企業情報リスト
const companies = ref<Company[]>([])

// ログイン済フラグ
const isLoggingIn = store.getters.isLoggingIn

// ログインユーザID取得
const loginUserId = () => {
  const loginUser = store.getters.loginUser
  return loginUser ? loginUser.id : null
}

// 選択中のタブ番号
const activeTabNumber = ref<number>(1)

const isReserveHistoryTab = computed(() => activeTabNumber.value === 1)
const isLikeListTab = computed(() => activeTabNumber.value === 2)

watch(activeTabNumber, (newValue, oldValue) => {
  if (newValue === 2) {
    getLikeCompanies()
  }
})

// 企業を検索する
const getLikeCompanies = async () => {
  companiesLoaded.value = false
  const response = await axios.get<Company[], AxiosResponse<Company[]>>('/api/like/companies', {
    params: {
      userId: loginUserId()
    }
  }).catch(e => e.response || e)
  companiesLoaded.value = true
  if (response.status == OK) {
    companies.value = response.data
  } else {
    store.dispatch('setError', response)
  }
}


</script>

<template>
  <div class="page-my-page">
    <Section class="page-my-page__section">
      <h2 class="page-menu-list__title">マイページメニュー</h2>
      <ul class="page-my-page__tab-menu-list">
        <li class="page-my-page__tab-menu" :class="{ 'is-active': isReserveHistoryTab }"
          @click.stop="activeTabNumber = 1">予約履歴</li>
        <li class="page-my-page__tab-menu" :class="{ 'is-active': isLikeListTab }" @click.stop="activeTabNumber = 2">
          お気に入り</li>
      </ul>
      <!-- 予約履歴 -->
      <div v-show="isReserveHistoryTab" class="page-my-page__reserve-list">
        <div class="page-my-page__reserve">
          <h3 class="page-my-page__reserve-title">2022/12/31(月)</h3>
          <div class="page-my-page__reserve-detail">
            <p class="page-my-page__reserve-detail-item is-time">
              <span>11:00</span>
              <span>〜</span>
              <span>12:00</span>
            </p>
            <p class="page-my-page__reserve-detail-item is-company-name">○○○会社</p>
            <p class="page-my-page__reserve-detail-item is-menu">Aコース</p>
            <p class="page-my-page__reserve-detail-item is-price">¥5,000</p>
            <button class="page-my-page__reserve-detail-item is-button" @click.stop>キャンセル</button>
          </div>
        </div>

        <div class="page-my-page__reserve">
          <h3 class="page-my-page__reserve-title">2022/1/1(月)</h3>
          <div class="page-my-page__reserve-detail">
            <p class="page-my-page__reserve-detail-item is-time">
              <span>01:00</span>
              <span>〜</span>
              <span>02:00</span>
            </p>
            <p class="page-my-page__reserve-detail-item is-company-name">あいうえおあいうえおあいうえおあいうえおあいうえお会社</p>
            <p class="page-my-page__reserve-detail-item is-menu">Aコースかきくけこかきくけこかきくけこかきくけこかきくけこ</p>
            <p class="page-my-page__reserve-detail-item is-price">¥999,999,999</p>
            <button class="page-my-page__reserve-detail-item is-button" @click.stop>キャンセル</button>
          </div>
        </div>
      </div>

      <!-- お気に入り一覧 -->
      <div v-show="isLikeListTab" class="page-my-page__like-list">
        <div v-show="!companiesLoaded" class="page-my-page__like-companies-loading">
          <vue-element-loading :active="true" :background-color="'#1c1c1c'" :color="'#fff'" :spinner="'spinner'"
            :text="'検索中'" />
        </div>
        <template v-if="companiesLoaded">
          <SearchResult v-for="company in getCompanies" :company="company" :executing="false" :show-like="false"
            @reserve="toMenuListPage">
          </SearchResult>
          <v-pagination class="page-my-page__pagination" v-show="getCompanies.length > 0" v-model="currentPageNumber"
            :pages="getTotalPageCount" :range-size="3" active-color="#dcc090" @update:modelValue="updateHandler" />
        </template>
      </div>
    </Section>

    <ConfirmModal :title="'ログイン確認'" :show="showLoginConfirmModal" :executing="false" :executeBtnlabel="'ログイン'"
      :cancelBtnlabel="'キャンセル'" @execute="toLoginPage" @cancel="closeLoginConfirmModal">
      この操作にはログインが必要です
    </ConfirmModal>

  </div>
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

.page-menu-list__title {
  color: #dcc090;
  font-size: 1.5em;
}

.page-my-page__tab-menu-list {
  column-gap: 2px;
  display: flex;
}

.page-my-page__tab-menu {
  background-color: #EDEAE2;
  border: transparent 1px solid;
  border-radius: 4px 4px 0 0;
  color: #1c1c1c;
  padding: 10px;

  &:hover {
    cursor: pointer;
  }
}

.page-my-page__tab-menu.is-active {
  background-color: #1c1c1c;
  border: #dcc090 1px solid;
  color: #dcc090;
}

.page-my-page__reserve-list {
  display: flex;
  flex-direction: column;
  row-gap: 30px;
  // @include mixins.mq(pc) {
  //   display: flex;
  //   flex-direction: column;
  //   row-gap: 30px;
  // }
}

.page-my-page__reserve {}

.page-my-page__reserve-title {
  background-color: #dcc090;
  color: #1c1c1c;
  font-weight: 700;
  padding: 10px;
}

.page-my-page__reserve-detail {
  background-color: #1c1c1c;
  border: #dcc090 1px solid;
  padding: 10px;

  @include mixins.mq(sp) {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  @include mixins.mq(tablet) {
    align-items: center;
    column-gap: 15px;
    display: grid;
    grid-template-columns: auto 1fr 1fr 120px 100px;
  }

  @include mixins.mq(pc) {
    align-items: center;
    column-gap: 20px;
    display: grid;
    grid-template-columns: 130px 1fr 1fr 150px 100px;
  }
}

.page-my-page__reserve-detail-item {
  color: #fff;

  @include mixins.mq(sp) {
    line-height: 1.5;
  }

  @include mixins.mq(tablet) {}

  @include mixins.mq(pc) {}
}

.page-my-page__reserve-detail-item.is-time {
  @include mixins.mq(sp) {
    text-align: left;
  }

  @include mixins.mq(tablet) {
    display: flex;
    flex-direction: column;
    row-gap: 5px;
    text-align: center;
  }

  @include mixins.mq(pc) {
    text-align: center;
  }
}

.page-my-page__reserve-detail-item.is-company-name {
  text-align: left;
}

.page-my-page__reserve-detail-item.is-menu {
  text-align: left;
}

.page-my-page__reserve-detail-item.is-price {
  text-align: right;

  @include mixins.mq(sp) {
    font-size: 1.2rem;
  }

  @include mixins.mq(tablet) {}

  @include mixins.mq(pc) {}
}

.page-my-page__reserve-detail-item.is-button {
  background-color: #F46D6D;
  border: transparent 1px solid;
  border-radius: 3px;
  color: #fff;
  font-size: 14px;
  letter-spacing: 0.1em;
  min-width: 100px;
  padding: 10px;
  text-align: center;

  &:focus {
    border: #1967d2 1px solid;
  }
}

.page-my-page__like-list {
  display: flex;
  flex-direction: column;
  max-width: 860px;
  padding-top: 0;
  width: 100%;

  @include mixins.mq(sp) {
    row-gap: 30px;
  }

  @include mixins.mq(tablet) {
    row-gap: 45px;
  }

  @include mixins.mq(pc) {
    row-gap: 60px;
  }
}

.page-my-page__like-companies-loading {
  @include mixins.mq(sp) {
    height: 100px;
  }

  @include mixins.mq(tablet) {
    height: 150px;
  }

  @include mixins.mq(pc) {
    height: 200px;
  }
}

.page-my-page__pagination {
  justify-content: end;
}
</style>
