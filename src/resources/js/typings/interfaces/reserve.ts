export interface Menu {
  id: string
  name: string
  minutesLength: number
}

export interface Reserve {
  date: string
  from: string
  to: string
}

export interface Holiday {
  id: number
  name: string
  abbreviation: string
}

export interface ReserveInfo {
  // 企業情報
  id: number
  name: string
  businessHoursFrom: string
  businessHoursTo: string
  // 選択したメニュー情報
  menu: Menu
  // 予約済の日程
  reserves: { [date: string]: Reserve[] }
  // 休業日
  holidays: Holiday[]
}

export interface DayOfWeek {
  id: number
  name: string
  abbreviation: string
}

export interface Day {
  year: number
  month: number
  date: number
  ymd: string
  dayOfWeek: DayOfWeek
}

export interface TimetableCell {
  from: string
  to: string
}


