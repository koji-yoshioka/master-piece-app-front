<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import { City, Company } from '@/typings/interfaces/search'
import axios, { AxiosResponse } from 'axios'
import { getContentHeight } from '@/util'
import InputCheckBox from '@/components/InputCheckBox.vue'
import InputNumber from '@/components/InputNumber.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
import Section from '@/components/Section.vue'
import SearchResult from '@/components/SearchResult.vue'
import VerticalTable from '@/components/VerticalTable.vue'
import Modal from '@/components/Modal.vue'

// 市区町村取得
const citiesLoaded = ref<boolean>(false)
// 企業情報取得
const companiesLoaded = ref<boolean>(false)

const companies = ref<Company[]>([])
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

// 企業ID
const prefectureId = ref<string>('13')
// 市区町村
const cities = ref<City[]>([])
// 選択した市区町村
const selectedCities = ref<string[]>([])

const showModal = ref<boolean>(false)
const close = () => {
  showModal.value = false
}

const showCitiesModal = () => {
  showModal.value = true
  loadCities()
}

const loadCities = async () => {
  // 未取得の場合のみ実施
  if (!citiesLoaded.value) {
    cities.value = (await axios.get<City[], AxiosResponse<City[]>>(`/api/city/${prefectureId.value}`)).data
    citiesLoaded.value = true
  }
}

const toggleAllCity = () => {
  if (selectedCities.value.length !== cities.value.length) {
    selectedCities.value = cities.value.map(city => city.name)
  } else {
    selectedCities.value = []
  }
}

const toggleCity = (value: string) => {
  const index = selectedCities.value.indexOf(value)
  if (index > -1) {
    selectedCities.value.splice(index, 1)
  } else {
    selectedCities.value.push(value)
  }
}

const search = async () => {
  companiesLoaded.value = false
  companies.value = (await axios.get<Company[], AxiosResponse<Company[]>>('/api/company', {
    params: {
      prefectureId: prefectureId.value,
      cities: selectedCities.value,
    }
  })).data
  companiesLoaded.value = true
}

onMounted(
  async () => {
    // prefectureId.value = (() => {
    //   const { prefectureId: paramPrefectureId } = useRoute().query
    //   return paramPrefectureId ? paramPrefectureId.toString() : ''
    // })()
    // console.log('prefectureId is ' + prefectureId.value)
    // if (!prefectureId.value) {
    //   console.log('falsy')
    // }
    // if (!prefectureId.value || !/^[1-9]|[1-3][0-9]|4[0-7]$/.test(prefectureId.value)) {
    //   // TODO:falsyまたは1~47の数値ではない場合
    //   console.log('error occured. prefectureId is ' + prefectureId.value)
    // }
    await search()
  })
</script>

<template>
  <div class="page-search">
    <Section class="page-search__condition-area">
      <VerticalTable :columnNames="['エリア', '料金', '条件']">
        <template v-slot:row1>
          <PrimaryButton class="page-search__area-filter-button" @click="showCitiesModal">エリアを絞り込む</PrimaryButton>
        </template>
        <template v-slot:row2>
          <div class="page-search__price-range-area">
            <InputNumber class="page-search__price-range" />〜
            <InputNumber class="page-search__price-range" />
          </div>
        </template>
        <template v-slot:row3>
          <PrimaryButton class="page-search__area-filter-button">条件を追加する</PrimaryButton>
        </template>
      </VerticalTable>
    </Section>

    <Section class="page-search__result-area">
      <vue-element-loading :active="!companiesLoaded" :background-color="'#1c1c1c'" :color="'#fff'"
        :is-full-screen="false" :spinner="'spinner'" :text="'検索中'" />
      <template v-if="companiesLoaded">
        <h2 class="page-search__result-title">
          <span class="page-search__result-title--strong">{{ getCompanies.length }}件</span>の検索結果があります
        </h2>
        <div class="page-search__result">
          <SearchResult v-for="company in getCompanies" :company="company"></SearchResult>
        </div>
        <v-pagination class="page-search__pagination" v-show="getCompanies.length > 0" v-model="currentPageNumber"
          :pages="getTotalPageCount" :range-size="3" active-color="#dcc090" @update:modelValue="updateHandler" />
      </template>
    </Section>







    <Modal :title="'市区町村を選択'" :show="showModal" @close="close">
      <template v-slot:loading>
        <vue-element-loading class="page-search__loading-cities" :active="!citiesLoaded" :background-color="'#1c1c1c'"
          :color="'#fff'" :is-full-screen="false" :spinner="'spinner'" :text="'取得中'" />
      </template>
      <template v-slot:content>
        <div class="page-search__city-options">
          <InputCheckBox class="page-search__city-option--all" :id="'city-all'" :value="'all'"
            :checked="selectedCities.length === cities.length" @click="toggleAllCity">全て</InputCheckBox>
          <InputCheckBox class="page-search__city-option" v-for="city in cities" :key="city.id" :id="`city-${city.id}`"
            :value="city.name" :checked="selectedCities.includes(city.name)" @click="toggleCity">
            {{ city.name }}</InputCheckBox>
        </div>
      </template>
    </Modal>
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

.page-search__area-filter-button {
  @include mixins.mq(sp) {
    width: 100%;
  }

  @include mixins.mq(tablet, pc) {
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
    align-items: center;
  }

  @include mixins.mq(pc) {
    align-items: center;
  }
}

.page-search__price-range ::v-deep(.c-input-number__input) {
  background-color: #eee;
  border: none;
}

.page-search__result-area {
  display: flex;
  flex-direction: column;
  max-width: 860px;
  padding-top: 0;
  row-gap: 20px;
  width: 100%;
}

.page-search__result-area.is-searching {
  position: relative;

  @include mixins.mq(sp) {
    height: 100px
  }

  @include mixins.mq(tablet) {
    height: 125px
  }

  @include mixins.mq(pc) {
    height: 150px
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

.page-search__loading-cities {
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
</style>
