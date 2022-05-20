<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useStore } from "@/store/store"
import axios, { AxiosResponse } from 'axios'
import { BaseInfo, Menu, Day, DayOfWeek, Reserve, Holiday, TimetableCell } from '@/typings/interfaces/reserve'
import Section from '@/components/Section.vue'
import ConfirmModal from '@/components/ConfirmModal.vue'

// Vuexが管理している認証状態
const store = useStore()
const userInfo = computed(() => store.getters.getLoginUser)

// 予約中
const reserving = ref<boolean>(false)

const router = useRouter()
const showModal = ref<boolean>(false)
const close = () => {
  showModal.value = false
}
const selectedTime = ref<{
  day: Day
  time: TimetableCell
}>()
const reserve = async (targetDay: Day | undefined, time: TimetableCell | undefined) => {
  if (!targetDay || !time) {
    return;
  }
  reserving.value = true
  await axios.post('/api/user/reserve', {
    companyId: companyId.value,
    userId: userInfo.value.id,
    menuId: menuId.value,
    date: targetDay.ymd,
    from: time.from,
    to: time.to,
  })
  // TODO:完了画面へ遷移する
  //reserves.value = (await axios.get<{ [date: string]: Reserve[] }, AxiosResponse<{ [date: string]: Reserve[] }>>(`/api/user/reserve/${companyId.value}`)).data
  //showModal.value = false
  //reserving.value = false
}

// 企業ID
const companyId = ref<string>('')
// メニューID
const menuId = ref<string>('')

// メニュー情報
const menu = ref<Menu>()
// 休業日
const holidays = ref<Holiday[]>([])
// 予約済みの日程
const reserves = ref<{ [date: string]: Reserve[] }>({ '': [] })
// 表示する全ての日付
const allDays = ref<Day[]>([])

const timetableCells = ref<TimetableCell[]>([])
const contentLoaded = ref<boolean>(false)

const selectTime = (day: Day, time: TimetableCell) => {
  showModal.value = true
  selectedTime.value = {
    day,
    time,
  }
}

const isHoliday = (dayOfWeekId: number) => holidays.value.map(holiday => holiday.id).includes(dayOfWeekId)
const isReserved = (targetYmd: string, from: string, to: string) => {
  // その日の予約一覧を取得
  const sameDayReserves = reserves.value[targetYmd]
  if (!sameDayReserves) {
    // その日に全く予約がない場合は「false:予約なし」を返す
    return false
  }

  return sameDayReserves.some(reserve =>
    (parseInt(from) < parseInt(reserve.to) && (parseInt(reserve.to) <= parseInt(to)))
    ||
    (parseInt(from) <= parseInt(reserve.from) && (parseInt(reserve.from) < parseInt(to)))
    ||
    (parseInt(from) >= parseInt(reserve.from) && parseInt(to) <= parseInt(reserve.to))
  )
}
onMounted(
  async () => {
    // パラメータ取得
    companyId.value = (() => {
      const { companyId } = useRoute().query
      return companyId ? companyId.toString() : ''
    })()
    menuId.value = (() => {
      const { menuId } = useRoute().query
      return menuId ? menuId.toString() : ''
    })()
    if (!companyId.value || !menuId.value) {
      // TODO:エラー処理
      console.log('falsy')
    }
    console.log('companyId', companyId.value)
    console.log('menuId', menuId.value)

    // 休業日と予約状況を取得
    const baseInfo = (await axios.get<BaseInfo, AxiosResponse<BaseInfo>>('/api/reserve/base-info', {
      params: {
        companyId: companyId.value,
        menuId: menuId.value,
      }
    })).data
    console.log('baseInfo', baseInfo)

    // 休業日
    holidays.value = baseInfo.holidays

    // 予約状況を取得
    reserves.value = baseInfo.reserves

    // 今日の日付〜1ヶ月後の日付までの日数
    const locale = new Date().toLocaleString('ja-JP', { timeZone: 'Asia/Tokyo' })
    const diffDays = (() => {
      const from = new Date(locale)
      const to = new Date(locale)
      to.setMonth(to.getMonth() + 1)
      return Math.floor((to.getTime() - from.getTime()) / 86400000)
    })()
    // 曜日マスタを取得
    const daysOfWeek = (await axios.get<DayOfWeek[], AxiosResponse<DayOfWeek[]>>('/api/day-of-week')).data
    for (let i = 0; i < diffDays; i++) {
      const day = new Date(locale)
      day.setDate(day.getDate() + i)
      allDays.value.push({
        year: day.getFullYear(),
        month: day.getMonth() + 1,
        date: day.getDate(),
        ymd: `${day.getFullYear()}${(day.getMonth() + 1).toString().padStart(2, '0')}${(day.getDate()).toString().padStart(2, '0')}`,
        dayOfWeek: daysOfWeek[day.getDay()]
      })
    }

    // 時間帯
    const fromMinutes = Number(baseInfo.businessHoursFrom.substring(0, 2)) * 60
      + Number(baseInfo.businessHoursFrom.substring(2, 4))
    const toMinutes = Number(baseInfo.businessHoursTo.substring(0, 2)) * 60
      + Number(baseInfo.businessHoursTo.substring(2, 4))

    // 予約枠の間隔
    const businessHoursInterval = baseInfo.menu.minutesLength

    for (let i = 0; i < (toMinutes - fromMinutes) / businessHoursInterval; i++) {
      timetableCells.value.push({
        from: (() => {
          // 開始時刻を分単位に変換
          const minutesUnit = fromMinutes + i * businessHoursInterval
          const hours = Math.floor(minutesUnit / 60)
          const minutes = minutesUnit % 60
          return hours.toString().padStart(2, '0').concat(minutes.toString().padStart(2, '0'))
        })(),
        to: (() => {
          // 開始時刻を分単位に変換
          const minutesUnit = fromMinutes + i * businessHoursInterval + businessHoursInterval
          const hours = Math.floor(minutesUnit / 60)
          const minutes = minutesUnit % 60
          return hours.toString().padStart(2, '0').concat(minutes.toString().padStart(2, '0'))
        })(),
      })
    }
    contentLoaded.value = true
  }
)
</script>

<template>
  <div class="page-reserve">
    <Section class="page-reserve__reserve-area">
      <h2 class="page-reserve__title">ご希望の来店日時を選択してください</h2>

      <div v-show="!contentLoaded" class="page-reserve__loading-area">
        <vue-element-loading class="page-reserve__loading" :active="true" :background-color="'#1c1c1c'" :color="'#fff'"
          :spinner="'spinner'" :text="'日程情報を読み込んでいます'" />
      </div>

      <div v-show="contentLoaded" class="page-reserve__content">
        <table class="page-reserve__table">
          <thead class="page-reserve__table-header">
            <tr class="page-reserve__table-header-row">
              <th class="page-reserve__table-header-col is-corner"></th>
              <th v-for="day in allDays" class="page-reserve__table-header-col">
                <div class="page-reserve__table-header-col-inner">
                  <span class="page-reserve__table-header-col-inner-text">{{ `${day.month}/${day.date}` }}</span>
                  <span class="page-reserve__table-header-col-inner-text">{{ `(${day.dayOfWeek.abbreviation})` }}</span>
                </div>
              </th>
            </tr>
          </thead>
          <tr v-for="(timetableCell, rowIndex) in timetableCells" class="page-reserve__table-data-row">
            <th class="page-reserve__table-header-col">
              <div class="page-reserve__vertical-table-header-col-inner">
                <span class="page-reserve__vertical-table-header-col-inner-text">{{ timetableCell.from }}</span>
                <span class="page-reserve__vertical-table-header-col-inner-text">〜</span>
                <span class="page-reserve__vertical-table-header-col-inner-text">{{ timetableCell.to }}</span>
              </div>
            </th>
            <template v-for="(day, index) in allDays">
              <td v-if="isHoliday(day.dayOfWeek.id) && rowIndex === 0" class="page-reserve__table-data is-holiday"
                :rowspan="timetableCells.length">定休日</td>
              <td v-if="!isHoliday(day.dayOfWeek.id)" class="page-reserve__table-data">
                <div class="page-reserve__table-data-col-inner">
                  <template v-if="isReserved(day.ymd, timetableCell.from, timetableCell.to)">
                    <font-awesome-icon class="page-reserve__table-data-col-inner-icon" :icon="['fas', 'xmark']"
                      size="2x" />
                  </template>
                  <template v-else>
                    <button class="page-reserve__table-data-col-inner-button"
                      @click="selectTime(day, timetableCell)">予約</button>
                  </template>
                </div>
              </td>
            </template>
          </tr>
        </table>
      </div>
    </Section>

    <ConfirmModal :title="'予約確認'" :show="showModal" :executing="reserving" :executeBtnlabel="'予約する'"
      :cancelBtnlabel="'キャンセル'" @execute="reserve(selectedTime?.day, selectedTime?.time)" @cancel="close">
      {{
          `${selectedTime?.day.month}/${selectedTime?.day.date}(${selectedTime?.day.dayOfWeek.abbreviation})：${selectedTime?.time.from}〜${selectedTime?.time.to}で予約しますか？`
      }}
    </ConfirmModal>


  </div>
</template>

<style lang="scss" scoped>
@use "~@/mixins";

.page-reserve {
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

.page-reserve__reserve-area {
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

.page-reserve__title {
  color: #dcc090;
  font-size: 1.5em;
}

.page-reserve__loading-area {
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

.page-reserve__loading {}

.page-reserve__content {
  overflow: scroll;
  max-height: 500px;
}

.page-reserve__table {
  border: #dcc090 1px solid;
  border-collapse: collapse;
  color: #dcc090;
  table-layout: fixed;
  width: 100%;
}

.page-reserve__table-header {}

.page-reserve__table-header-row {}

.page-reserve__table-header-col {
  background-color: #1c1c1c;
  border: #dcc090 1px solid;
  left: 0;
  position: sticky;
  top: 0;
  vertical-align: middle;
  width: 50px;
  z-index: 1;

  &:before {
    border: 1px solid #dcc090;
    box-sizing: content-box;
    content: "";
    position: absolute;
    top: -1px;
    left: -1px;
    width: 100%;
    height: 100%;
  }
}

.page-reserve__table-header-col.is-corner {
  z-index: 2;
}

.page-reserve__table-header-col-inner {
  align-items: center;
  display: flex;
  flex-direction: column;
  font-size: 10px;
  gap: 5px;
  padding: 5px;
}

.page-reserve__table-header-col-inner-text {}

.page-reserve__table-data-row {}

.page-reserve__vertical-table-header-col-inner {
  align-items: center;
  display: flex;
  flex-direction: column;
  font-size: 10px;
  gap: 5px;
  padding: 5px;
}

.page-reserve__vertical-table-header-col-inner-text {}

.page-reserve__table-data {
  border: #dcc090 1px solid;
  vertical-align: middle;
}

.page-reserve__table-data.is-holiday {
  letter-spacing: 1em;
  text-align: center;
  writing-mode: vertical-rl;
}

.page-reserve__table-data-col-inner {
  display: flex;
  justify-content: center;
}

.page-reserve__table-data-col-inner-icon {
  color: #fff;
}

.page-reserve__table-data-col-inner-button {
  background-color: #03A768;
  border: transparent 1px solid;
  border-radius: 3px;
  color: #fff;
  font-size: 14px;
  padding: 5px;

  &:focus {
    border: #1967d2 1px solid;
  }
}
</style>
