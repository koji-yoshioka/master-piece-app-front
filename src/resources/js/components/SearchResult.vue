<script setup lang="ts">
import { computed, PropType } from 'vue'
import { Company } from '@/typings/interfaces/search'

const props = defineProps({
  company: {
    type: Object as PropType<Company>,
    required: true
  },
})

const getLogo = computed(() =>
  props.company.logo
    ? `https://s3-ap-northeast-1.amazonaws.com/master-piece-company-images/${props.company.id}/logo/${props.company.logo}`
    : `https://s3-ap-northeast-1.amazonaws.com/master-piece-company-images/common/no-image.jpeg`
)

const getAddress = computed(() => {
  const prefecture = props.company.prefecture ? props.company.prefecture : ''
  const city = props.company.city ? props.company.city : ''
  const restAddress = props.company.restAddress ? props.company.restAddress : ''
  return [prefecture, city, restAddress].join(' ')
})

const getBusinessHours = computed(() => {
  const businessHoursFrom = props.company.businessHoursFrom
    ? props.company.businessHoursFrom.substring(0, 2) + ':' + props.company.businessHoursFrom.substring(2)
    : '-'
  const businessHoursTo = props.company.businessHoursTo
    ? props.company.businessHoursTo.substring(0, 2) + ':' + props.company.businessHoursTo.substring(2)
    : '-'
  return [businessHoursFrom, businessHoursTo].join(' 〜 ')
})

const getHolidays = computed(() => {
  return props.company.holidays.map(holiday => holiday.name).join(' ')
})
</script>

<template>
  <div class="c-search-result">
    <p class="c-search-result__name">{{ company.name }}</p>
    <div class="c-search-result__category-area">
      <div class="c-search-result__category" v-for="sellingPoint in company.sellingPoints">
        {{ sellingPoint.name }}
      </div>
    </div>
    <div class="c-search-result__content">
      <figure class="c-search-result__logo-area">
        <img class="c-search-result__logo" :src="getLogo" alt="COMPANY LOGO" />
      </figure>
      <div class="c-search-result__overview">
        <p class="c-search-result__comment">{{ company.comment }}</p>

        <div class="c-search-result__address-area">
          <label class="c-search-result__address-label">所在地</label>
          <p class="c-search-result__address">
            {{ getAddress }}</p>
        </div>

        <div class="c-search-result__access-area">
          <label class="c-search-result__access-label">アクセス</label>
          <p class="c-search-result__access">{{ company.nearestStation }}</p>
        </div>

        <div class="c-search-result__business-date-area">
          <label class="c-search-result__business-date-label">営業時間</label>
          <p class="c-search-result__business-date">{{ getBusinessHours }}</p>
          <label class="c-search-result__closed-label">休業日</label>
          <p class="c-search-result__closed">{{ getHolidays }}</p>
        </div>

        <div class="c-search-result__link-area-group">
          <div class="c-search-result__link-area">
            <font-awesome-icon :icon="['far', 'heart']" size="2x" />
            <!-- <font-awesome-icon :icon="['fas', 'heart']" size="2x" /> -->
            <p>お気に入り</p>
          </div>
          <div class="c-search-result__link-area">
            <font-awesome-icon :icon="['fas', 'calendar-days']" size="2x" />
            <router-link :to="{ name: 'reserve', query: { companyId: company.id } }" exact>予約する</router-link>
          </div>
          <div class="c-search-result__link-area">
            <font-awesome-icon :icon="['far', 'comment-dots']" size="2x" />
            <p>レビュー</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
@use "~@/mixins";

.c-search-result {
  border: #dcc090 2px solid;
  width: 100%;

  @include mixins.mq(sp) {
    padding: 10px;
  }

  @include mixins.mq(tablet, pc) {
    padding: 20px;
  }
}

.c-search-result__name {
  color: #dcc090;
  font-size: 1.25rem;
}

.c-search-result__category-area {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  margin: 10px 0;
}

.c-search-result__category {
  background-color: #fff;
  border-color: #988989;
  border-radius: 10px;
  color: #988989;
  display: inline-block;
  padding: 10px;
}

.c-search-result__content {
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

.c-search-result__logo-area {
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

.c-search-result__logo {
  left: 0;
  position: absolute;
  top: 0;
  width: 100%;
}

.c-search-result__overview {
  display: flex;
  flex-direction: column;
  flex-grow: 1;
  row-gap: 10px;
}

.c-search-result__comment {
  background-color: #edeae2;
  min-height: 120px;
  padding: 5px;
}

.c-search-result__address-area {
  display: flex;
  flex-direction: column;
  row-gap: 10px;
  width: 100%;
}

.c-search-result__address-label {
  color: #dcc090;
}

.c-search-result__address {
  color: #fff;
}

.c-search-result__access-area {
  display: flex;
  flex-direction: column;
  row-gap: 10px;
  width: 100%;
}

.c-search-result__access-label {
  color: #dcc090;
}

.c-search-result__access {
  color: #fff;
}

.c-search-result__business-date-area {
  column-gap: 10px;
  display: flex;
}

.c-search-result__business-date-label {
  color: #dcc090;
}

.c-search-result__business-date {
  color: #fff;
}

.c-search-result__closed-label {
  color: #dcc090;
}

.c-search-result__closed {
  color: #fff;
}

.c-search-result__link-area-group {
  align-items: center;
  background-color: #dcc090;
  display: flex;
  column-gap: 20px;
  padding: 10px;

  @include mixins.mq(sp) {
    align-items: flex-start;
    flex-direction: column;
    row-gap: 10px;
  }

  @include mixins.mq(tablet) {}

  @include mixins.mq(pc) {}
}

.c-search-result__link-area {
  align-items: center;
  display: flex;
  column-gap: 20px;
}
</style>
