<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useStore } from "@/store/store"
import axios, { AxiosResponse } from 'axios'
import { Company } from '@/typings/interfaces/search'
import { OK } from '@/util'
import Section from '@/components/Section.vue'
import SearchResult from '@/components/SearchResult.vue'

// グローバル情報
const store = useStore()
// ルーティング情報
const router = useRouter()
// ログイン済フラグ
const isLoggingIn = store.getters.isLoggingIn

// 企業情報取得済フラグ
const companiesLoaded = ref<boolean>(false)
// 企業情報リスト
const companies = ref<Company[]>([])

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

// ログインユーザID取得
const loginUserId = () => {
  const loginUser = store.getters.loginUser
  return loginUser ? loginUser.id : null
}

// メニューリストページへ遷移する
const toMenuList = (companyId: number) => {
  router.push({ name: 'menu-list', query: { companyId } })
}

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

onMounted(async () => {
  if (isLoggingIn) {
    getLikeCompanies()
  } else {
    router.push({ name: 'login' })
  }
})
</script>

<template>
  <div class="page-like-company">
    <Section class="page-like-company__section">
      <h2 class="page-like-company__title">お気に入り一覧</h2>
      <div class="page-like-company__like-list">
        <div v-show="!companiesLoaded" class="page-like-company__loading">
          <vue-element-loading :active="true" :background-color="'#1c1c1c'" :color="'#fff'" :spinner="'spinner'"
            :text="'検索中'" />
        </div>
        <template v-if="companiesLoaded">
          <SearchResult v-for="company in getCompanies" :company="company" :executing="false" :show-like="false"
            @reserve="toMenuList">
          </SearchResult>
          <v-pagination class="page-like-company__pagination" v-show="getCompanies.length > 0"
            v-model="currentPageNumber" :pages="getTotalPageCount" :range-size="3" active-color="#dcc090"
            @update:modelValue="updateHandler" />
        </template>
      </div>

    </Section>

  </div>
</template>

<style lang="scss" scoped>
@use "~@/mixins";

.page-like-company {
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

.page-like-company__section {
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

.page-like-company__title {
  color: #dcc090;
  font-size: 1.5em;
}

.page-like-company__like-list {
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

.page-like-company__loading {
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

.page-like-company__pagination {
  justify-content: end;
}
</style>
