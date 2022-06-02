<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useStore } from "@/store/store"
import { useVuelidate } from '@vuelidate/core'
import { helpers } from '@vuelidate/validators'
import { City, Company, SellingPoint } from '@/typings/interfaces/search'
import { OK } from '@/util'
import axios, { AxiosResponse } from 'axios'
import InputCheckBox from '@/components/InputCheckBox.vue'
import InputNumber from '@/components/InputNumber.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
import Section from '@/components/Section.vue'
import SearchResult from '@/components/SearchResult.vue'
import VerticalTable from '@/components/VerticalTable.vue'
import Modal from '@/components/Modal.vue'
import ConfirmModal from '@/components/ConfirmModal.vue'

// グローバル情報
const store = useStore()
// ログイン済フラグ
const isLoggingIn = store.getters.isLoggingIn

// ルーティング情報
const router = useRouter()

// 都道府県ID ※現時点では東京のみを想定しているため、固定値で設定
const prefectureId = ref<string>('13')
// 市区町村取得済フラグ
const citiesLoaded = ref<boolean>(false)
// セールスポイント取得済フラグ
const sellingPointsLoaded = ref<boolean>(false)
// 企業情報取得済フラグ
const companiesLoaded = ref<boolean>(false)
// 市区町村リスト
const cities = ref<City[]>([])
// 選択した市区町村
const selectedCities = ref<string[]>([])
// 料金(下限)
const inputPriceMin = ref<number | null>(null)
// 料金(上限)
const inputPriceMax = ref<number | null>(null)
// セールスポイントリスト
const sellingPoints = ref<SellingPoint[]>([])
// 選択したセールスポイントのID
const selectedSellingPointIds = ref<string[]>([])
// 企業情報リスト
const companies = ref<Company[]>([])
// 市区町村モーダル表示フラグ
const showCitiesModal = ref<boolean>(false)
// こだわり条件モーダル表示フラグ
const showSellingPointsModal = ref<boolean>(false)
// ログイン確認モーダル表示フラグ
const showLoginConfirmModal = ref<boolean>(false)
// 評価モーダル表示フラグ
const showStarRatingModal = ref<boolean>(false)

// 非同期処理実行中の企業ID
const executingCompanyIds = ref<number[]>([])

// --start バリデーション関連
const fields = ref({
  priceMin: null,
  priceMax: null,
})
const rules = {
  priceMin: {
    min: helpers.withMessage('0円以上で入力してください。', () => !inputPriceMin.value || inputPriceMin.value >= 0),
    range: helpers.withMessage('上限より小さな金額を入力してください。',
      () => {
        if (!inputPriceMin.value || !inputPriceMax.value) {
          return true
        }
        return inputPriceMin.value <= inputPriceMax.value
      }),
  },
  priceMax: {
    min: helpers.withMessage('0円以上で入力してください。', (value: number | null) => !value || value >= 0),
    // 下限より小さな値があればエラー
    range: helpers.withMessage('下限より大きな金額を入力してください。',
      () => {
        if (!inputPriceMin.value || !inputPriceMax.value) {
          return true
        }
        return inputPriceMin.value <= inputPriceMax.value
      }),
  }
}
const v$ = useVuelidate(rules, fields)
// --end

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

// 市区町村モーダルを閉じる
const closeCitiesModal = () => {
  showCitiesModal.value = false
}

// こだわり条件モーダルを閉じる
const closeSellingPointsModal = () => {
  showSellingPointsModal.value = false
}

// ログイン確認モーダルを閉じる
const closeLoginConfirmModal = () => {
  showLoginConfirmModal.value = false
}

// 評価モーダルを閉じる
const closeStarRatingModal = () => {
  showStarRatingModal.value = false
}

// 市区町村を取得
const getCities = async () => {
  showCitiesModal.value = true
  // 未取得の場合のみ実施
  if (!citiesLoaded.value) {
    const response = await axios.get<City[], AxiosResponse<City[]>>('api/cities', {
      params: {
        prefectureId: prefectureId.value,
      }
    }).catch(e => e.response || e)
    if (response.status === OK) {
      citiesLoaded.value = true
      cities.value = response.data
    } else {
      showCitiesModal.value = false
      store.dispatch('setError', response)
    }
  }
}

// 全ての市区町村を選択
const toggleAllCity = () => {
  if (selectedCities.value.length !== cities.value.length) {
    selectedCities.value = cities.value.map(city => city.name)
  } else {
    selectedCities.value = []
  }
}

// 市区町村を選択
const toggleCity = (value: string) => {
  const index = selectedCities.value.indexOf(value)
  if (index > -1) {
    selectedCities.value.splice(index, 1)
  } else {
    selectedCities.value.push(value)
  }
}

// こだわり条件を取得
const getSellingPoints = async () => {
  showSellingPointsModal.value = true
  // 未取得の場合のみ実施
  if (!sellingPointsLoaded.value) {
    const response = await axios.get<SellingPoint[], AxiosResponse<SellingPoint[]>>('/api/selling-point')
      .catch(e => e.response || e)
    if (response.status === OK) {
      sellingPointsLoaded.value = true
      sellingPoints.value = response.data
    } else {
      showSellingPointsModal.value = false
      store.dispatch('setError', response)
    }
  }
}

// 全てのこだわり条件を選択
const toggleAllSellingPoint = () => {
  if (selectedSellingPointIds.value.length !== sellingPoints.value.length) {
    selectedSellingPointIds.value = sellingPoints.value.map(sellingPoint => sellingPoint.id.toString())
  } else {
    selectedSellingPointIds.value = []
  }
}

// こだわり条件を選択
const toggleSellingPoint = (value: string) => {
  const index = selectedSellingPointIds.value.indexOf(value)
  if (index > -1) {
    selectedSellingPointIds.value.splice(index, 1)
  } else {
    selectedSellingPointIds.value.push(value)
  }
}

const openStarRatingModal = (companyId: number) => {
  showStarRatingModal.value = true
}


// 企業を検索する
const search = async () => {
  companiesLoaded.value = false
  const response = await axios.get<Company[], AxiosResponse<Company[]>>('/api/companies', {
    params: {
      userId: loginUserId(),
      prefectureId: prefectureId.value,
      cities: selectedCities.value,
      priceMin: inputPriceMin.value,
      priceMax: inputPriceMax.value,
      sellingPointIds: selectedSellingPointIds.value,
    }
  }).catch(e => e.response || e)
  companiesLoaded.value = true
  if (response.status == OK) {
    companies.value = response.data
  } else {
    store.dispatch('setError', response)
  }
}

// ログインユーザID取得
const loginUserId = () => {
  const loginUser = store.getters.loginUser
  return loginUser ? loginUser.id : null
}

// お気に入りに登録/削除する
const toggleLike = async (companyId: number) => {
  // 実行中のID追加
  executingCompanyIds.value.push(companyId)
  if (isLoggingIn) {
    const response = await axios.post('/api/like', {
      companyId,
      userId: loginUserId(),
    }).catch(e => e.response || e)
    if (response.status !== OK) {
      store.dispatch('setError', response)
    } else {
      // お気に入りのフラグ値を切り替える
      companies.value.map(company => {
        if (company.id === companyId) {
          company.userLike = !company.userLike
        }
        return company
      })
    }
  } else {
    showLoginConfirmModal.value = true
  }
  // ID削除
  const index = executingCompanyIds.value.indexOf(companyId)
  executingCompanyIds.value.splice(index, 1)
}

// メニューリストページへ遷移する
const toMenuListPage = (companyId: number) => {
  if (isLoggingIn) {
    router.push({ name: 'menu-list', query: { companyId } })
  } else {
    showLoginConfirmModal.value = true
  }
}

// ログインページへ遷移する
const toLogin = () => {
  router.push({ name: 'login' })
}

onMounted(
  async () => {
    // 初期表示時に検索
    await search()
  })
</script>

<template>
  <div class="page-search">
    <Section class="page-search__condition-area">
      <VerticalTable :columnNames="['エリア', '料金', 'こだわり条件']">
        <template v-slot:row1>
          <PrimaryButton class="page-search__filter-button" @click="getCities">エリアを絞り込む</PrimaryButton>
        </template>
        <template v-slot:row2>
          <div class="page-search__price-range-area">
            <InputNumber class="page-search__price-range" :min="0" :errors="v$.priceMin.$errors"
              @update:modelValue="inputPriceMin = $event" v-model="v$.priceMin.$model" />
            <span class="page-search__price-range-symbol">〜</span>
            <InputNumber class="page-search__price-range" :min="0" :errors="v$.priceMax.$errors"
              @update:modelValue="inputPriceMax = $event" v-model="v$.priceMax.$model" />
          </div>
        </template>
        <template v-slot:row3>
          <PrimaryButton class="page-search__filter-button" @click="getSellingPoints">条件を追加する</PrimaryButton>
        </template>
      </VerticalTable>
      <div class="page-search__search-button-area">
        <PrimaryButton class="page-search__search-button" @click="search">検索する</PrimaryButton>
      </div>
    </Section>

    <Section class="page-search__result-area">
      <div v-show="!companiesLoaded" class="page-search__search-loading-area">
        <vue-element-loading :active="true" :background-color="'#1c1c1c'" :color="'#fff'" :spinner="'spinner'"
          :text="'検索中'" />
      </div>
      <template v-if="companiesLoaded">
        <h2 class="page-search__result-title">
          <span class="page-search__result-title--strong">{{ getCompanies.length }}件</span>の検索結果があります
        </h2>
        <div class="page-search__result">
          <SearchResult v-for="company in getCompanies" :company="company"
            :executing="executingCompanyIds.indexOf(company.id) > -1" @toggleLike="toggleLike"
            @review="openStarRatingModal" @reserve="toMenuListPage"></SearchResult>
        </div>
        <v-pagination class="page-search__pagination" v-show="getCompanies.length > 0" v-model="currentPageNumber"
          :pages="getTotalPageCount" :range-size="3" active-color="#dcc090" @update:modelValue="updateHandler" />
      </template>
    </Section>

    <Modal :title="'市区町村を選択'" :show="showCitiesModal" @close="closeCitiesModal">
      <template v-slot:loading>
        <vue-element-loading class="page-search__modal-content-loading" :active="!citiesLoaded"
          :background-color="'#1c1c1c'" :color="'#fff'" :is-full-screen="true" :spinner="'spinner'"
          :text="'エリア情報を読み込んでいます'" />
      </template>
      <template v-slot:content>
        <div class="page-search__city-options">
          <template v-if="citiesLoaded">
            <InputCheckBox class="page-search__city-option--all" :id="'city-all'" :value="'all'"
              :checked="selectedCities.length === cities.length" @click="toggleAllCity">全て</InputCheckBox>
            <InputCheckBox class="page-search__city-option" v-for="city in cities" :key="city.id"
              :id="`city-${city.id}`" :value="city.name" :checked="selectedCities.includes(city.name)"
              @click="toggleCity">
              {{ city.name }}</InputCheckBox>
          </template>
        </div>
      </template>
    </Modal>

    <Modal :title="'こだわり条件を選択'" :show="showSellingPointsModal" @close="closeSellingPointsModal">
      <template v-slot:loading>
        <vue-element-loading class="page-search__modal-content-loading" :active="!sellingPointsLoaded"
          :background-color="'#1c1c1c'" :color="'#fff'" :is-full-screen="true" :spinner="'spinner'"
          :text="'条件を読み込んでいます'" />
      </template>
      <template v-slot:content>
        <div class="page-search__selling-point-options">
          <template v-if="sellingPointsLoaded">
            <InputCheckBox class="page-search__selling-point-option--all" :id="'selling-point-all'" :value="'all'"
              :checked="selectedSellingPointIds.length === sellingPoints.length" @click="toggleAllSellingPoint">全て
            </InputCheckBox>
            <InputCheckBox class="page-search__selling-point-option" v-for="sellingPoint in sellingPoints"
              :key="sellingPoint.id" :id="`selling-point-${sellingPoint.id}`" :value="sellingPoint.id.toString()"
              :checked="selectedSellingPointIds.includes(sellingPoint.id.toString())" @click="toggleSellingPoint">
              {{ sellingPoint.name }}</InputCheckBox>
          </template>
        </div>
      </template>
    </Modal>

    <ConfirmModal :title="'ログイン確認'" :show="showLoginConfirmModal" :executing="false" :executeBtnlabel="'ログイン'"
      :cancelBtnlabel="'キャンセル'" @execute="toLogin" @cancel="closeLoginConfirmModal">
      この操作にはログインが必要です
    </ConfirmModal>

    <ConfirmModal :title="'ログイン確認'" :show="showStarRatingModal" :executing="false" :executeBtnlabel="'保存'"
      :cancelBtnlabel="'キャンセル'" @execute="toLogin" @cancel="closeStarRatingModal">
    </ConfirmModal>

  </div>
</template>

<style lang="scss" scoped>
@use "~@/mixins";

.page-search {
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

.page-search__condition-area {
  max-width: 860px;
  width: 100%;
}

.page-search__filter-button {
  @include mixins.mq(sp) {
    width: 100%;
  }

  @include mixins.mq(tablet) {
    width: 200px;
  }

  @include mixins.mq(pc) {
    width: 200px;
  }
}

.page-search__price-range-area {
  display: flex;
  gap: 10px;

  @include mixins.mq(sp) {
    flex-direction: column;
  }

  @include mixins.mq(tablet) {
    flex-direction: row;
  }

  @include mixins.mq(pc) {
    flex-direction: row;
  }
}

.page-search__price-range ::v-deep(.c-input-number__input) {
  background-color: #eee;
  border: none;

  &:focus {
    border: #1967d2 2px solid;
  }
}

.page-search__price-range-symbol {
  @include mixins.mq(sp) {
    line-height: 1;
  }

  @include mixins.mq(tablet) {
    // 入力エリアと同じline-heightを設定
    line-height: 50px;
  }

  @include mixins.mq(pc) {
    // 入力エリアと同じline-heightを設定
    line-height: 50px;
  }
}

.page-search__search-button-area {
  display: flex;
  justify-content: center;

  @include mixins.mq(sp) {
    margin-top: 40px;
  }

  @include mixins.mq(tablet) {
    margin-top: 50px;
  }

  @include mixins.mq(pc) {
    margin-top: 60px;
  }
}

.page-search__search-button {
  width: 200px;
}

.page-search__result-area {
  display: flex;
  flex-direction: column;
  max-width: 860px;
  padding-top: 0;
  row-gap: 20px;
  width: 100%;
}

.page-search__search-loading-area {
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

.page-search__result-title {
  color: #dcc090;
}

.page-search__result-title--strong {
  font-size: 1.5rem;
}

.page-search__result {
  display: flex;
  flex-direction: column;

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

.page-search__pagination {
  justify-content: end;
}

.page-search__modal-content-loading {
  opacity: 0.8;
}

.page-search__city-options {
  display: grid;

  @include mixins.mq(sp) {
    grid-template-columns: repeat(2, 1fr);
    max-height: 300px;
    min-height: 150px;
    width: 250px;
  }

  @include mixins.mq(tablet) {
    grid-template-columns: repeat(3, 1fr);
    max-height: 500px;
    min-height: 300px;
    width: 500px;
  }

  @include mixins.mq(pc) {
    grid-template-columns: repeat(3, 1fr);
    max-height: 500px;
    min-height: 300px;
    width: 500px;
  }
}

.page-search__city-option--all {
  @include mixins.mq(sp) {
    grid-column-start: 1;
    grid-column-end: 3;
  }

  @include mixins.mq(tablet) {
    grid-column-start: 1;
    grid-column-end: 4;
  }

  @include mixins.mq(pc) {
    grid-column-start: 1;
    grid-column-end: 4;
  }
}

.page-search__city-option {}

.page-search__selling-point-options {
  display: grid;
  min-height: 80px;

  @include mixins.mq(sp) {
    grid-template-columns: repeat(2, 1fr);
    max-height: 300px;
    width: 250px;
  }

  @include mixins.mq(tablet) {
    grid-template-columns: repeat(3, 1fr);
    max-height: 500px;
    width: 500px;
  }

  @include mixins.mq(pc) {
    grid-template-columns: repeat(3, 1fr);
    max-height: 500px;
    width: 500px;
  }
}

.page-search__selling-point-option--all {
  @include mixins.mq(sp) {
    grid-column-start: 1;
    grid-column-end: 3;
  }

  @include mixins.mq(tablet) {
    grid-column-start: 1;
    grid-column-end: 4;
  }

  @include mixins.mq(pc) {
    grid-column-start: 1;
    grid-column-end: 4;
  }
}

.page-search__selling-point-option {}
</style>
