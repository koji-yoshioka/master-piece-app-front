export interface CompanyMenus {
  id: number
  name: string
  menus: {
    id: number
    name: string
    comment: string
    price: number
  }[]
}
