export interface City {
  id: number
  name: string
}

export interface Company {
  id: number | null
  name: string | null
  tel: string | null
  prefecture: string | null
  city: string | null
  restAddress: string | null
  nearestStation: string | null
  businessHoursFrom: string | null
  businessHoursTo: string | null
  comment: string | null
  logo: string | null
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
}
