<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useStore } from "@/store/store"
import axios, { AxiosResponse } from 'axios'


import { Reserve } from '@/typings/interfaces/useReserve'
import { OK } from '@/util'
import Section from '@/components/Section.vue'


// グローバル情報
const store = useStore()
// ルーティング情報
const router = useRouter()

// ログイン済フラグ
const isLoggingIn = store.getters.isLoggingIn
// 予約取得済フラグ
const reservesLoaded = ref<boolean>(false)

// 予約リスト
const reserves = ref<Reserve[]>([])


// ログインユーザID取得
const loginUserId = () => {
  const loginUser = store.getters.loginUser
  return loginUser ? loginUser.id : null
}

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

const getReserveList = async () => {
  reservesLoaded.value = false
  const response = await axios.get<Reserve[], AxiosResponse<Reserve[]>>('/api/user-reserves', {
    params: {
      userId: loginUserId()
    }
  }).catch(e => e.response || e)
  reservesLoaded.value = true
  if (response.status == OK) {
    reserves.value = response.data
  } else {
    store.dispatch('setError', response)
  }
}

onMounted(async () => {
  if (isLoggingIn) {
    getReserveList()
  } else {
    router.push({ name: 'login' })
  }
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
              <button class="page-reserve-history__reserve-detail-item is-button" @click.stop>キャンセル</button>
            </div>
          </div>
          <v-pagination class="page-reserve-history__pagination" v-show="reserves.length > 0"
            v-model="currentPageNumber" :pages="getTotalPageCount" :range-size="3" active-color="#dcc090"
            @update:modelValue="updateHandler" />
        </template>
      </div>
    </Section>
  </div>
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
</style>
