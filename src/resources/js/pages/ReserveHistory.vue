<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useStore as useAuthStore } from '@/store/auth'
import { httpService } from '@/services/httpService'
import { Reserve } from '@/typings/interfaces/useReserve'
import Section from '@/components/Section.vue'
import ConfirmModal from '@/components/ConfirmModal.vue'

// 認証情報
const authStore = useAuthStore()
// ルーティング情報
const router = useRouter()
// ログイン済フラグ
const isLoggingIn = !!authStore.getters.loginUser

// キャンセル実行中フラグ
const canceling = ref<boolean>(false)
// 確認モーダル表示フラグ
const showConfirmModal = ref<boolean>(false)
// 予約取得済フラグ
const reservesLoaded = ref<boolean>(false)
// 予約リスト
const reserves = ref<Reserve[]>([])

// 選択したキャンセル対象の予約ID
const selectedReserveId = ref<number | null>(null)

// --start ページング関連
const currentPageNumber = ref<number>(1)
const perPage = ref<number>(5)
const getReserves = computed(() => {
  let current = currentPageNumber.value * perPage.value
  let start = current - perPage.value;
  return reserves.value.slice(start, current);
})
const updateHandler = (pageNumber: number) => {
  currentPageNumber.value = pageNumber
}
const getTotalPageCount = computed(() => Math.ceil(reserves.value.length) / perPage.value)
// --end

// ログインユーザID取得
const loginUserId = () => {
  const loginUser = authStore.getters.loginUser
  return loginUser ? loginUser.id : null
}

const getFormattedTime = (time: string) => {
  const hour = time.substring(0, 2)
  const minute = time.substring(2, 4)
  return `${hour}:${minute}`
}

const getDate = (date: string) => {
  const year = date.substring(0, 4)
  const month = date.substring(4, 6)
  const day = date.substring(6, 8)
  const dayOfWeek = ['日', '月', '火', '水', '木', '金', '土'][new Date(`${year}/${month}/${day}`).getDay()]
  return `${year}/${month}/${day}(${dayOfWeek})`
}

// 確認モーダルを閉じる
const closeConfirmModal = () => {
  showConfirmModal.value = false
}

// キャンセル対象を選択
const selectMenu = (reserveId: number) => {
  selectedReserveId.value = reserveId
  showConfirmModal.value = true
}

// キャンセル
const cancel = async () => {
  if (!selectedReserveId.value) {
    return
  }
  canceling.value = true
  const isSuccess = await httpService.cancelReserve(selectedReserveId.value)
  if (isSuccess) {
    reserves.value = reserves.value.filter(reserve => reserve.id !== selectedReserveId.value)
  }
  canceling.value = false
  showConfirmModal.value = false
}

onMounted(async () => {
  reservesLoaded.value = false
  const resReserves = await httpService.getReserveHistory(loginUserId())
  reservesLoaded.value = true
  reserves.value = resReserves
})
</script>

<template>
  <div class="page-reserve-history">
    <Section class="page-reserve-history__section">
      <h2 class="page-reserve-history__title">予約履歴</h2>
      <div class="page-reserve-history__reserve-list">
        <div v-show="!reservesLoaded" class="page-reserve-history__loading">
          <vue-element-loading :active="true" :background-color="'#1c1c1c'" :color="'#fff'" :spinner="'spinner'"
            :text="'検索中'" />
        </div>
        <template v-if="reservesLoaded">
          <div v-for="reserve in getReserves" :key="reserve.id" class="page-reserve-history__reserve">
            <h3 class="page-reserve-history__reserve-title">{{ getDate(reserve.date) }}</h3>
            <div class="page-reserve-history__reserve-detail">
              <p class="page-reserve-history__reserve-detail-item is-time">
                <span>{{ getFormattedTime(reserve.from) }}</span>
                <span>〜</span>
                <span>{{ getFormattedTime(reserve.to) }}</span>
              </p>
              <p class="page-reserve-history__reserve-detail-item is-company-name">{{ reserve.companyName }}</p>
              <p class="page-reserve-history__reserve-detail-item is-menu">{{ reserve.menuName }}</p>
              <p class="page-reserve-history__reserve-detail-item is-price">{{ `¥${reserve.price.toLocaleString()}` }}
              </p>
              <button class="page-reserve-history__reserve-detail-item is-button"
                @click="selectMenu(reserve.id)">キャンセル</button>
            </div>
          </div>
          <v-pagination class="page-reserve-history__pagination" v-show="getReserves.length > 0"
            v-model="currentPageNumber" :pages="getTotalPageCount" :range-size="3" active-color="#dcc090"
            @update:modelValue="updateHandler" />
        </template>
        <div v-if="reservesLoaded && getReserves.length === 0" class="page-reserve-history__empty">
          予約はありません
        </div>
      </div>
    </Section>
  </div>

  <ConfirmModal :title="'キャンセル確認'" :show="showConfirmModal" :executing="canceling" :loadingLabel="'予約をキャンセルしています'"
    :executeBtnlabel="'はい'" :cancelBtnlabel="'いいえ'" @execute="cancel" @cancel="closeConfirmModal">
    予約をキャンセルしますか？
  </ConfirmModal>

</template>

<style lang="scss" scoped>
@use "~@/mixins";

.page-reserve-history {
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

.page-reserve-history__section {
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

.page-reserve-history__title {
  color: #dcc090;
  font-size: 1.5em;
}

.page-reserve-history__reserve-list {
  display: flex;
  flex-direction: column;
  row-gap: 30px;
}

.page-reserve-history__loading {
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

.page-reserve-history__reserve {}

.page-reserve-history__reserve-title {
  background-color: #dcc090;
  color: #1c1c1c;
  font-weight: 700;
  padding: 10px;
}

.page-reserve-history__reserve-detail {
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

.page-reserve-history__reserve-detail-item {
  color: #fff;

  @include mixins.mq(sp) {
    line-height: 1.5;
  }

  @include mixins.mq(tablet) {}

  @include mixins.mq(pc) {}
}

.page-reserve-history__reserve-detail-item.is-time {
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

.page-reserve-history__reserve-detail-item.is-company-name {
  text-align: left;
}

.page-reserve-history__reserve-detail-item.is-menu {
  text-align: left;
}

.page-reserve-history__reserve-detail-item.is-price {
  text-align: right;

  @include mixins.mq(sp) {
    font-size: 1.2rem;
  }

  @include mixins.mq(tablet) {}

  @include mixins.mq(pc) {}
}

.page-reserve-history__reserve-detail-item.is-button {
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

.page-reserve-history__pagination {
  justify-content: end;
}

.page-reserve-history__empty {
  background-color: #edeae2;
  color: #1c1c1c;
  height: 300px;
  line-height: 300px;
  text-align: center;
}
</style>
