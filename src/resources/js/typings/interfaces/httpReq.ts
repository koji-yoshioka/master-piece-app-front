export interface SignUpReq {
  name: string
  email: string
  password: string
  password_confirmation: string
}

export interface LoginReq {
  email: string
  password: string
}

export interface ChangePasswordReq {
  currentPassword: string
  newPassword: string
  newPassword_confirmation: string
}

export interface FindCompanyReq {
  userId: number | null
  prefectureId: number
  cities: string[]
  priceMin: number | null
  priceMax: number | null
  sellingPointIds: string[]
}

export interface ToggleLikeReq {
  companyId: number
  userId: number
}

export interface ReserveReq {
  companyId: number
  userId: number
  menuId: number
  date: string
  from: string
  to: string
}
