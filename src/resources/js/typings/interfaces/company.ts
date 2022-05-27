export interface Company {
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
