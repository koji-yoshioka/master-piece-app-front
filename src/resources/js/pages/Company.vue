<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { httpService } from '@/services/httpService'
import { useStore as useAuthStore } from '@/store/auth'
import { Company } from '@/typings/interfaces/company'
import Section from '@/components/Section.vue'
import VerticalTable from '@/components/VerticalTable.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
import ConfirmModal from '@/components/ConfirmModal.vue'

// 認証情報
const authStore = useAuthStore()
// ルーティング情報
const router = useRouter()
// ログイン済フラグ
const isLoggingIn = !!authStore.getters.loginUser

// 企業ID
const companyId = ref<string>('')
// 企業情報取得済フラグ
const companyLoaded = ref<boolean>(false)
// お気に入り更新フラグ
const toggleLikeExecuting = ref<boolean>(false)
// ログイン確認モーダル表示フラグ
const showLoginConfirmModal = ref<boolean>(false)
// 企業情報
const company = ref<Company>()

// ログイン確認モーダルを閉じる
const closeLoginConfirmModal = () => {
  showLoginConfirmModal.value = false
}
// ログインページへ遷移する
const toLogin = () => {
  router.push({ name: 'login' })
}
// ロゴ取得
const getLogo = computed(() =>
  `https://static.master-piece.site/common/no-image.jpeg`
)
// 営業時間取得
const getBusinessHours = computed(() => {
  const businessHoursFrom = company?.value?.businessHoursFrom
    ? company.value.businessHoursFrom.substring(0, 2) + ':' + company.value.businessHoursFrom.substring(2)
    : '-'
  const businessHoursTo = company?.value?.businessHoursTo
    ? company.value.businessHoursTo.substring(0, 2) + ':' + company.value.businessHoursTo.substring(2)
    : '-'
  return [businessHoursFrom, businessHoursTo].join(' 〜 ')
})

// 休業日取得
const getHolidays = computed(() => {
  return company?.value?.holidays.map(holiday => holiday.name).join(' ')
})

// 住所取得
const getAddress = computed(() => {
  const prefecture = company?.value?.prefecture ? company.value.prefecture : ''
  const city = company?.value?.city ? company.value.city : ''
  const restAddress = company?.value?.restAddress ? company.value.restAddress : ''
  return [prefecture, city, restAddress].join(' ')
})

// ログインユーザID取得
const loginUserId = () => {
  const loginUser = authStore.getters.loginUser
  return loginUser ? loginUser.id : null
}

// お気に入りに登録/削除する
const toggleLike = async () => {
  if (isLoggingIn) {
    if (toggleLikeExecuting.value) {
      return
    }
    toggleLikeExecuting.value = true

    const isSuccess = await httpService.toggleLike({
      companyId: Number(companyId.value),
      userId: loginUserId(),
    })
    if (isSuccess && company.value) {
      company.value.userLike = !company.value.userLike
    }
    toggleLikeExecuting.value = false
  } else {
    showLoginConfirmModal.value = true
  }
}

// メニューリストページへ遷移する
const toMenuListPage = () => {
  if (isLoggingIn) {
    router.push({ name: 'menu-list', query: { companyId: companyId.value } })
  } else {
    showLoginConfirmModal.value = true
  }
}

onMounted(async () => {
  companyId.value = (() => {
    const { companyId } = useRoute().query
    return companyId ? companyId.toString() : ''
  })()
  if (!companyId) {
    router.push({ name: 'error' })
  }
  const resCompany = await httpService.findCompanyById(Number(companyId.value), loginUserId())
  if (!resCompany) {
    // nullは異常
    return
  }
  company.value = resCompany
  companyLoaded.value = true
})
</script>

<template>
  <div class="page-company">
    <div v-show="!companyLoaded" class="page-company__loading-area">
      <vue-element-loading class="page-company__loading" :active="true" :background-color="'#1c1c1c'" :color="'#fff'"
        :spinner="'spinner'" :text="'企業情報を読み込んでいます'" />
    </div>
    <template v-if="companyLoaded">
      <Section class="page-company__section">
        <div class="page-company__top-area">
          <h3 class="page-company__top-title">{{ company?.name }}</h3>
          <div class="page-company__category-area">
            <div v-for="sellingPoint in company?.sellingPoints" class="page-company__category">
              {{ sellingPoint.name }}
            </div>
          </div>
          <div class="page-company__top-content">
            <div class="page-company__logo-area">
              <img class="page-company__logo" :src="getLogo" alt="COMPANY LOGO" />
            </div>
            <div class="page-company__overview">
              <div class="page-company__overview-item">
                <label class="page-company__overview-item-label">TEL</label>
                <p class="page-company__overview-item-text">{{ company?.tel }}</p>
              </div>
              <div class="page-company__overview-item">
                <label class="page-company__overview-item-label">営業時間</label>
                <p class="page-company__overview-item-text">{{ getBusinessHours }}</p>
              </div>
              <div class="page-company__overview-item">
                <label class="page-company__overview-item-label">休業日</label>
                <p class="page-company__overview-item-text">{{ getHolidays }}</p>
              </div>
              <div class="page-company__overview-item">
                <label class="page-company__overview-item-label">所在地</label>
                <p class="page-company__overview-item-text">
                  <span v-if="company?.zipCode">{{ `〒${company.zipCode}` }}<br></span>
                  <span>{{ getAddress }}</span>
                </p>
              </div>
              <div class="page-company__overview-item">
                <label class="page-company__overview-item-label">アクセス</label>
                <p class="page-company__overview-item-text">{{ company?.nearestStation }}</p>
              </div>
              <div class="page-company__link-area-group">
                <div class="page-company__link-area is-like" @click.stop="toggleLike">
                  <font-awesome-icon v-if="company?.userLike" class="page-company__like-icon-checked"
                    :icon="['fas', 'heart']" size="2x" />
                  <font-awesome-icon v-else class="page-company__like-icon-unchecked" :icon="['fas', 'heart']"
                    size="2x" />
                  <font-awesome-icon v-if="toggleLikeExecuting" class="page-company__like-icon-toggling"
                    :icon="['fas', 'spinner']" size="2x" :spin="true" />
                  <p class="page-company__link is-like">お気に入り</p>
                </div>
                <div class="page-company__link-area" @click.stop="toMenuListPage">
                  <font-awesome-icon :icon="['fas', 'calendar-days']" size="2x" />
                  <p class="page-company__link is-reserve">予約する</p>
                </div>
                <!-- <div class="page-company__link-area" @click.stop>
                  <font-awesome-icon :icon="['far', 'star']" size="2x" />
                  <p class="page-company__link is-review">評価する</p>
                </div> -->
              </div>
            </div>
          </div>
        </div>
      </Section>
      <Section class="page-company__section">
        <div class="page-company__content-area">
          <div class="page-company__image-area">
            <figure class="page-company__main-image-area">
              <img class="page-company__main-image" :src="getLogo" alt="main-image">
            </figure>
            <div class="page-company__sub-image-group">
              <figure class="page-company__sub-image-area">
                <img class="page-company__sub-image" :src="getLogo" alt="sub-image-1">
              </figure>
              <figure class="page-company__sub-image-area">
                <img class="page-company__sub-image" :src="getLogo" alt="sub-image-2">
              </figure>
              <figure class="page-company__sub-image-area">
                <img class="page-company__sub-image" :src="getLogo" alt="sub-image-3">
              </figure>
            </div>
          </div>
          <div class="page-company__comment-area">
            <p class="page-company__comment">{{ company?.comment }}</p>
          </div>
          <VerticalTable :columnNames="['お支払い方法', '備考']">
            <template v-slot:row1>
              <div class="page-company__payment-method-area">
                <span v-for="paymentMethod in company?.paymentMethods" class="page-company__payment-method">
                  {{ paymentMethod.name }}
                </span>
              </div>
            </template>
            <template v-slot:row2>
              <div class="page-company__payment-note-area">
                <p class="page-company__payment-note">{{ company?.note }}</p>
              </div>
            </template>
          </VerticalTable>
        </div>
        <div class="page-company__submit-area">
          <PrimaryButton class="page-company__submit" @click="toMenuListPage">予約する</PrimaryButton>
        </div>
      </Section>
    </template>

    <ConfirmModal :title="'ログイン確認'" :show="showLoginConfirmModal" :executing="false" :executeBtnlabel="'ログイン'"
      :cancelBtnlabel="'キャンセル'" @execute="toLogin" @cancel="closeLoginConfirmModal">
      この操作にはログインが必要です
    </ConfirmModal>
  </div>
</template>

<style lang="scss" scoped>
@use "~@/mixins";

.page-company {
  align-items: center;
  display: flex;
  flex-direction: column;

  @include mixins.mq(sp) {
    padding-left: 10px;
    padding-right: 10px;
  }

  @include mixins.mq(tablet) {
    padding-left: 50px;
    padding-right: 50px;
  }

  @include mixins.mq(pc) {
    padding-left: 70px;
    padding-right: 70px;
  }
}

.page-company__loading-area {
  width: 100%;

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

.page-company__loading {}

.page-company__section {
  max-width: 860px;
  width: 100%;
}

.page-company__top-area {
  border: #dcc090 2px solid;
  width: 100%;

  @include mixins.mq(sp) {
    padding: 10px;
  }

  @include mixins.mq(tablet) {
    padding: 20px;
  }

  @include mixins.mq(pc) {
    padding: 20px;
  }
}

.page-company__top-title {
  color: #dcc090;
  font-size: 1.25rem;
}

.page-company__category-area {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  margin: 20px 0;
}

.page-company__category {
  background-color: #fff;
  border-color: #988989;
  border-radius: 10px;
  color: #988989;
  display: inline-block;
  padding: 10px;
}

.page-company__top-content {
  column-gap: 20px;
  display: flex;

  @include mixins.mq(sp) {
    flex-direction: column;
    row-gap: 10px;
  }

  // @include mixins.mq(tablet) {
  // }
  // @include mixins.mq(pc) {
  // }
}

.page-company__logo-area {
  border: #dcc090 2px solid;
  height: 100%;
  position: relative;
  width: 200px;

  @include mixins.mq(sp) {
    width: 100%;
  }

  @include mixins.mq(tablet, pc) {}

  &::before {
    background-color: #fff;
    content: "";
    display: block;
    padding-bottom: 100%;
  }
}

.page-company__logo {
  left: 0;
  position: absolute;
  top: 0;
  width: 100%;
}

.page-company__overview {
  display: flex;
  flex: 1;
  flex-wrap: wrap;

  @include mixins.mq(sp) {
    flex-direction: column;
    gap: 20px;
  }

  @include mixins.mq(tablet) {
    column-gap: 30px;
    row-gap: 10px;
  }

  @include mixins.mq(pc) {
    column-gap: 30px;
    row-gap: 10px;
  }
}

.page-company__overview-item {
  display: flex;
  flex-direction: column;
  row-gap: 10px;
}

.page-company__overview-item-label {
  color: #dcc090;
}

.page-company__overview-item-text {
  color: #fff;
  line-height: 1.5;
}

.page-company__link-area-group {
  align-items: center;
  background-color: #dcc090;
  display: flex;
  column-gap: 20px;
  padding: 5px 10px;
  width: 100%;

  @include mixins.mq(sp) {
    align-items: flex-start;
    flex-direction: column;
    row-gap: 10px;
  }

  @include mixins.mq(tablet) {}

  @include mixins.mq(pc) {}
}

.page-company__link-area {
  align-items: center;
  display: flex;
  column-gap: 20px;

  &:hover {
    cursor: pointer;
  }
}

.page-company__link-area.is-like {
  position: relative;
}

.page-company__like-icon-toggling {
  color: #1c1c1c;
  opacity: 0.5;
  position: absolute;
}

.page-company__like-icon-checked {
  color: #d13939;
}

.page-company__like-icon-unchecked {
  color: #edeae2;
}

.page-company__link {}

.page-company__link.is-like {}

.page-company__link.is-reserve {}

.page-company__link.is-review {}

.page-company__content-area {
  border: #dcc090 2px solid;
  display: flex;
  flex-direction: column;
  row-gap: 20px;
  width: 100%;

  @include mixins.mq(sp) {
    padding: 10px;
  }

  @include mixins.mq(tablet) {
    padding: 20px;
  }

  @include mixins.mq(pc) {
    padding: 20px;
  }
}

.page-company__image-area {
  @include mixins.mq(sp) {
    display: flex;
    flex-direction: column;
    row-gap: 20px;
  }

  @include mixins.mq(tablet) {
    display: flex;
    justify-content: space-between;
  }

  @include mixins.mq(pc) {
    display: flex;
    justify-content: space-between;
  }
}

.page-company__main-image-area {
  border: #dcc090 2px solid;
  position: relative;

  @include mixins.mq(sp) {
    width: 100%;
  }

  @include mixins.mq(tablet) {
    width: 70%;
  }

  @include mixins.mq(pc) {
    width: 70%;
  }

  &::before {
    background-color: #fff;
    content: "";
    display: block;
    padding-bottom: 100%;
  }
}

.page-company__main-image {
  height: 100%;
  object-fit: cover;
  position: absolute;
  top: 0;
  width: 100%;
}

.page-company__sub-image-group {
  @include mixins.mq(sp) {
    display: flex;
    justify-content: space-between;
  }

  @include mixins.mq(tablet) {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    width: 20%;
  }

  @include mixins.mq(pc) {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    width: 20%;
  }
}

.page-company__sub-image-area {
  border: #dcc090 2px solid;
  position: relative;

  @include mixins.mq(sp) {
    width: 30%;
  }

  @include mixins.mq(tablet) {
    width: 100%;
  }

  @include mixins.mq(pc) {
    width: 100%;
  }

  &::before {
    background-color: #fff;
    content: "";
    display: block;
    padding-bottom: 100%;
  }
}

.page-company__sub-image {
  height: 100%;
  object-fit: cover;
  position: absolute;
  top: 0;
  width: 100%;
}

.page-company__comment-area {
  background-color: #edeae2;
  min-height: 120px;
  padding: 5px;
}

.page-company__comment {}

.page-company__payment-method-area {
  @include mixins.mq(sp) {
    display: flex;
    flex-direction: column;
    row-gap: 10px;
  }

  @include mixins.mq(tablet) {
    display: flex;
    flex-wrap: wrap;
    gap: 20px
  }

  @include mixins.mq(pc) {
    display: flex;
    flex-wrap: wrap;
    gap: 20px
  }
}

.page-company__submit-area {
  display: flex;
  justify-content: center;
  width: 100%;

  @include mixins.mq(sp) {
    margin-top: 40px;
  }

  @include mixins.mq(tablet) {
    margin-top: 60px;
  }

  @include mixins.mq(pc) {
    margin-top: 80px;
  }
}

.page-company__submit {
  @include mixins.mq(sp) {
    width: 150px;
  }

  @include mixins.mq(tablet) {
    width: 175px;
  }

  @include mixins.mq(pc) {
    width: 200px;
  }
}
</style>
