<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { flashMessage } from '@smartweb/vue-flash-message'
import { httpService } from '@/services/httpService'
import { CompanyMenus } from '@/typings/interfaces/menuList'
import Section from '@/components/Section.vue'

const props = defineProps({
  companyId: {
    type: String,
    required: true,
  },
})

// ルーティング情報
const router = useRouter()

// メニューリスト
const companyMenus = ref<CompanyMenus | null>(null)
// メニュー取得済フラグ
const menusLoaded = ref<boolean>(false)

const toReserve = (menuId: number) => {
  router.push({ name: 'reserve', params: { companyId: props.companyId, menuId } });
}

onMounted(async () => {
  if (!/^\d+$/.test(props.companyId)) {
    menusLoaded.value = true
    flashMessage.show({
      type: 'error',
      text: 'URLのパラメータが不正です。',
    })
    return
  }
  // メニュー取得
  const menus = await httpService.getMenus(Number(props.companyId))
  menusLoaded.value = true
  companyMenus.value = menus
})
</script>

<template>
  <div class="page-menu-list">
    <Section class="page-menu-list__menu-list-area">
      <h2 class="page-menu-list__menu-list-title">メニューを選択してください</h2>
      <div v-show="!menusLoaded" class="page-menu-list__loading-area">
        <vue-element-loading class="page-menu-list__loading" :active="true" :background-color="'#1c1c1c'"
          :color="'#fff'" :spinner="'spinner'" :text="'メニューを読み込んでいます'" />
      </div>
      <div v-for="menu in companyMenus ? companyMenus.menus : [] " :key="menu.id" v-show="menusLoaded"
        class="page-menu-list__menu">
        <h3 class="page-menu-list__menu-title">{{ menu.name }}</h3>
        <div class="page-menu-list__menu-detail">
          <p class="page-menu-list__menu-detail-comment">{{ menu.comment }}</p>
          <p class="page-menu-list__menu-detail-price">{{ `¥${menu.price.toLocaleString()}` }}</p>
          <button class="page-menu-list__menu-detail-forward" @click="toReserve(menu.id)">日程を選択</button>
        </div>
      </div>
      <div v-if="menusLoaded && (!companyMenus || !companyMenus.menus || companyMenus.menus.length === 0)"
        class="page-menu-list__empty">
        ご利用可能なメニューはありません
      </div>
    </Section>
  </div>
</template>

<style lang="scss" scoped>
@use "~@/mixins";

.page-menu-list {
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

.page-menu-list__menu-list-area {
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

.page-menu-list__menu-list-title {
  color: #dcc090;
  font-size: 1.5em;
}

.page-menu-list__loading-area {
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

.page-menu-list__loading {}

.page-menu-list__menu {}

.page-menu-list__menu-title {
  background-color: #dcc090;
  color: #1c1c1c;
  font-weight: 700;
  padding: 10px;
}

.page-menu-list__menu-detail {
  background-color: #1c1c1c;
  border: #dcc090 1px solid;
  display: flex;
  padding: 10px;

  @include mixins.mq(sp) {
    flex-direction: column;
    gap: 10px;
  }

  @include mixins.mq(tablet) {
    align-items: center;
    gap: 20px;
    justify-content: flex-end;
  }

  @include mixins.mq(pc) {
    align-items: center;
    gap: 30px;
    justify-content: flex-end;
  }
}

.page-menu-list__menu-detail-comment {
  color: #fff;
  flex-grow: 1;
}

.page-menu-list__menu-detail-price {
  color: #fff;
  min-width: 80px;

  @include mixins.mq(sp) {}

  @include mixins.mq(tablet) {
    text-align: right;
  }

  @include mixins.mq(pc) {
    text-align: right;
  }
}

.page-menu-list__menu-detail-forward {
  background-color: #03A768;
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

.page-menu-list__empty {
  background-color: #edeae2;
  color: #1c1c1c;
  height: 300px;
  line-height: 300px;
  text-align: center;
}
</style>
