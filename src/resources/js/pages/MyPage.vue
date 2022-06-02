<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useStore } from "@/store/store"
import { Company } from '@/typings/interfaces/search'
import Section from '@/components/Section.vue'

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

// TODO:後ほど外す
// onMounted(async () => {
//   if (!isLoggingIn) {
//     router.push({ name: 'login' })
//   }
// })
</script>

<template>
  <div class="page-my-page">
    <Section class="page-my-page__section">
      <h2 class="page-my-page__title">マイページメニュー</h2>
      <ul class="page-my-page__menu-list">
        <li class="page-my-page__menu">
          <router-link to="/profile" exact>
            <div class="page-my-page__menu-content">
              <font-awesome-icon :icon="['far', 'address-card']" size="5x" />
              <p class="page-my-page__menu-title">プロフィール編集</p>
            </div>
          </router-link>
        </li>
        <li class="page-my-page__menu">
          <router-link to="/password-change" exact>
            <div class="page-my-page__menu-content">
              <font-awesome-icon :icon="['fas', 'key']" size="5x" />
              <p class="page-my-page__menu-title">パスワード変更</p>
            </div>
          </router-link>
        </li>
        <li class="page-my-page__menu">
          <router-link to="/reserve-history" exact>
            <div class="page-my-page__menu-content">
              <font-awesome-icon :icon="['fas', 'history']" size="5x" />
              <p class="page-my-page__menu-title">予約履歴</p>
            </div>
          </router-link>
        </li>
        <li class="page-my-page__menu">
          <router-link to="/like-company-list" exact>
            <div class="page-my-page__menu-content">
              <font-awesome-icon :icon="['fas', 'heart-circle-check']" size="5x" />
              <p class="page-my-page__menu-title">お気に入り一覧</p>
            </div>
          </router-link>
        </li>
      </ul>
    </Section>
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
  }
}

.page-my-page__menu {
  background-color: #1c1c1c;
  border: 2px solid #dcc090;
  border-radius: 10px;
  color: #d7d5d3;
  padding: 10px;
  position: relative;
  // width: 160px;

  @include mixins.mq(sp) {}

  @include mixins.mq(tablet) {}

  @include mixins.mq(pc) {
    // width: 160px;
  }
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
