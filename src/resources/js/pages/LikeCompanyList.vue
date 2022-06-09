<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useStore as useAuthStore } from '@/store/auth'
import { httpService } from '@/services/httpService'
import { Company } from '@/typings/interfaces/search'
import Section from '@/components/Section.vue'
import SearchResult from '@/components/SearchResult.vue'

// 認証情報
const authStore = useAuthStore()
// ルーティング情報
const router = useRouter()

// 企業情報取得済フラグ
const companiesLoaded = ref<boolean>(false)
// 企業情報リスト
const companies = ref<Company[]>([])

// --start ページング関連
const currentPageNumber = ref<number>(1)
const perPage = ref<number>(5)
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
  const loginUser = authStore.getters.loginUser
  return loginUser ? loginUser.id : null
}

// メニューリストページへ遷移する
const toMenuList = (companyId: number) => {
  router.push({ name: 'menu-list', query: { companyId } })
}

// 企業を検索する
const getLikes = async () => {
  companiesLoaded.value = false
  const resCompanies = await httpService.getLikes(loginUserId())
  companiesLoaded.value = true
  companies.value = resCompanies
}

onMounted(async () => {
  getLikes()
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
        <div v-if="companiesLoaded && getCompanies.length === 0" class="ppage-like-company__empty">
          お気に入りはありません
        </div>
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

.ppage-like-company__empty {
  background-color: #edeae2;
  color: #1c1c1c;
  height: 300px;
  line-height: 300px;
  text-align: center;
}
</style>
