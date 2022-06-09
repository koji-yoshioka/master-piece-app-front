export interface ResUser {
  id: number
  name: string
  is_guest: boolean
}

export interface ResCity {
  id: number
  name: string
}

export interface ResSellingPoint {
  id: number
  name: string
}

export interface ResCompany {
  id: number
  name: string
  tel: string | null
  zipCode: string | null
  prefecture: string | null
  city: string | null
  restAddress: string | null
  nearestStation: string | null
  businessHoursFrom: string | null
  businessHoursTo: string | null
  comment: string | null
  note: string | null
  logo: string | null
  images: {
    displayNo: number
    fileName: string | null
  }[]
  sellingPoints: {
    id: number
    name: string
  }[]
  paymentMethods: {
    id: number
    name: string
  }[]
  holidays: {
    id: number
    name: string
  }[]
  userLike: boolean
}

export interface ResReserve {
  id: number
  date: string
  from: string
  to: string
  companyName: string
  menuName: string
  price: number
}

export interface ResCompanyMenus {
  id: number
  name: string
  menus: {
    id: number
    name: string
    comment: string
    price: number
  }[]
}

export interface ResReserveInfo {
  // 企業情報
  id: number
  name: string
  businessHoursFrom: string
  businessHoursTo: string
  // 選択したメニュー情報
  menu: {
    id: string
    name: string
    minutesLength: number
  }
  // 予約済の日程
  reserves: {
    [date: string]: {
      date: string
      from: string
      to: string
    }[]
  }
  // 休業日
  holidays: {
    id: number
    name: string
    abbreviation: string
  }[]
}

export interface ResDayOfWeek {
  id: number
  name: string
  abbreviation: string
}
