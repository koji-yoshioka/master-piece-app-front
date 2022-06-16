import axios, { AxiosError, AxiosResponse } from 'axios'
import { ChangePasswordReq, FindCompanyReq, LoginReq, ReserveReq, ResetPasswordReq, SignUpReq, ToggleLikeReq } from '@/typings/interfaces/httpReq'
import { ResCity, ResCompany, ResCompanyMenus, ResDayOfWeek, ResPasswordReset, ResReserveInfo, ResReserve, ResSellingPoint } from '@/typings/interfaces/httpRes'
import { flashMessage } from '@smartweb/vue-flash-message'

// ログイン情報取得
const getLoginUser = async () => {
  try {
    const response = await axios.get('/api/user')
    return response.data
      ? {
        id: response.data.id,
        name: response.data.name,
        email: response.data.email,
        isGuest: response.data.is_guest,
        imageFileName: response.data.image_file_name,
      }
      : null
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
    return null
  }
}

// 会員登録
const signUp = async (data: SignUpReq) => {
  try {
    const response = await axios.post('/api/sign-up', data)
    flashMessage.show({
      type: 'success',
      text: '会員登録が完了しました。',
    })
    return {
      id: response.data.id,
      name: response.data.name,
      email: response.data.email,
      isGuest: false,
      imageFileName: null,
    }
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
    return null
  }
}

// ログイン
const login = async (data: LoginReq) => {
  try {
    const response = await axios.post('/api/login', data)
    flashMessage.show({
      type: 'success',
      text: 'ログインに成功しました。',
    })
    return {
      id: response.data.id,
      name: response.data.name,
      email: response.data.email,
      isGuest: response.data.is_guest,
      imageFileName: response.data.image_file_name,
    }
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
    return null
  }
}

// パスワードリセットメール送信 ※成否の真偽値を返す
const sendPasswordResetMail = async (email: string) => {
  try {
    await axios.post('/api/forgot', {
      email
    })
    flashMessage.show({
      type: 'success',
      text: 'メールが送信されました。',
    })
    return true
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
    return false
  }
}

// リセット対象を取得
const getPasswordReset = async (token: string) => {
  try {
    const response = await axios.get<ResPasswordReset, AxiosResponse<ResPasswordReset>>('/api/password-reset', {
      params: {
        token
      }
    })
    return response.data
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
    return null
  }
}

// パスワードリセット ※成否の真偽値を返す
const resetPassword = async (data: ResetPasswordReq) => {
  try {
    await axios.post('/api/password-reset', data)
    flashMessage.show({
      type: 'success',
      text: 'パスワードリセットが完了しました。',
    })
    return true
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
    return false
  }
}

// ゲストログイン
const guestLogin = async () => {
  try {
    const response = await axios.post('/api/login/guest')
    flashMessage.show({
      type: 'success',
      text: 'ログインに成功しました。',
    })
    return {
      id: response.data.id,
      name: response.data.name,
      email: response.data.email,
      isGuest: response.data.is_guest,
      imageFileName: response.data.image_file_name,
    }
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
    return null
  }
}

// ログアウト
const logout = async () => {
  try {
    await axios.post('/api/logout')
    flashMessage.show({
      type: 'success',
      text: 'ログアウトしました。',
    })
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
  }
  // ログアウトは常にnullを返す
  return null
}

// ユーザ情報更新 ※成否の真偽値を返す
const updateUser = async (formData: FormData) => {
  try {
    await axios.post(`/api/user`, formData, {
      headers: {
        'X-HTTP-Method-Override': 'PUT',
        'content-type': 'multipart/form-data',
      }
    })
    return true
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
    return false
  }
}

// パスワード変更 ※成否の真偽値を返す
const changePassword = async (data: ChangePasswordReq) => {
  try {
    const response = await axios.post('/api/password-change', data)
    flashMessage.show({
      type: 'success',
      text: 'パスワードが変更されました。',
    })
    return true
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
    return false
  }
}

// 退会 ※成否の真偽値を返す
const withdraw = async () => {
  try {
    await axios.delete('/api/user')
    flashMessage.show({
      type: 'success',
      text: '退会処理が完了しました。',
    })
    return true
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
    return false
  }
}

// 市区町村を取得
const findCities = async (prefectureId: number) => {
  try {
    const response = await axios.get<ResCity[], AxiosResponse<ResCity[]>>(`/api/prefecture/city/${prefectureId}`)
    return response.data
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
    return []
  }
}

// こだわり条件を取得
const findSellingPoints = async () => {
  try {
    const response = await axios.get<ResSellingPoint[], AxiosResponse<ResSellingPoint[]>>('/api/selling-point')
    return response.data
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
    return []
  }
}

// 企業情報を取得(複数)
const findCompanies = async (data: FindCompanyReq) => {
  try {
    const response = await axios.get<ResCompany[], AxiosResponse<ResCompany[]>>('/api/company', {
      params: {
        ...data
      }
    })
    return response.data
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
    return []
  }
}

// 企業情報を取得(1件)
const findCompanyById = async (companyId: number, userId: number | null) => {
  try {
    const response = await axios.get<ResCompany, AxiosResponse<ResCompany>>(`/api/company/${companyId}`, {
      params: {
        userId
      }
    })
    return response.data
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
    return null
  }
}

// お気に入りに登録/削除 ※成否の真偽値を返す
const toggleLike = async (data: ToggleLikeReq) => {
  try {
    const response = await axios.post('/api/like', data)
    return true
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
    return false
  }
}

// 予約履歴を取得
const getReserveHistory = async (userId: number) => {
  try {
    const response = await axios.get<ResReserve[], AxiosResponse<ResReserve[]>>(`/api/reserve/${userId}`)
    return response.data
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
    return []
  }
}

// お気に入り一覧を取得
const getLikes = async (userId: number) => {
  try {
    const response = await axios.get<ResCompany[], AxiosResponse<ResCompany[]>>(`/api/like/${userId}`)
    return response.data
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
    return []
  }
}

// メニューリストを取得
const getMenus = async (companyId: number) => {
  try {
    const response = await axios.get<ResCompanyMenus, AxiosResponse<ResCompanyMenus>>(`/api/company/menu/${companyId}`)
    return response.data
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
    return null
  }
}

// 予約状況を取得
const getReserveInfo = async (companyId: number, menuId: number) => {
  try {
    const response = await axios.get<ResReserveInfo, AxiosResponse<ResReserveInfo>>('/api/reserve/reserve-info', {
      params: {
        companyId,
        menuId,
      }
    })
    return response.data
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
    return null
  }
}

// 曜日マスタを取得
const getDaysOfWeek = async () => {
  try {
    const response = await axios.get<ResDayOfWeek[], AxiosResponse<ResDayOfWeek[]>>('/api/day-of-week')
    return response.data
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
    return []
  }
}

// 予約 ※成否の真偽値を返す
const reserve = async (data: ReserveReq) => {
  try {
    await axios.post('/api/reserve', data)
    flashMessage.show({
      type: 'success',
      text: '予約が完了しました。',
    })
    return true
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
    return false
  }
}

// 予約キャンセル ※成否の真偽値を返す
const cancelReserve = async (reserveId: number) => {
  try {
    await axios.delete(`/api/reserve/${reserveId}`)
    flashMessage.show({
      type: 'success',
      text: '予約をキャンセルしました。',
    })
    return true
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
    return false
  }
}

// お問い合わせ ※成否の真偽値を返す
const postContact = async (customerEmail: string, comment: string) => {
  try {
    await axios.post('/api/contact', {
      customerEmail,
      comment,
    })
    flashMessage.show({
      type: 'info',
      text: 'お問い合わせ内容が送信されました。',
    })
    return true
  } catch (e) {
    if (axios.isAxiosError(e) && e.response) {
      httpErrorHandler(e)
    }
    return false
  }
}

// 共通処理：エラーハンドリング
const httpErrorHandler = (e: AxiosError) => {
  switch (e.response?.status) {
    case 400:
      flashMessage.show({
        type: 'error',
        text: '送信内容に不備があります。',
      })
      break
    case 401:
      const message = e.response.data.message;
      flashMessage.show({
        type: 'error',
        text: message,
      })
      break
    case 403:
      flashMessage.show({
        type: 'error',
        text: '要求された操作を実行する権限がありません。',
      })
      break
    case 404:
      flashMessage.show({
        type: 'error',
        text: '該当する情報が見つかりません。',
      })
      break
    case 413:
      flashMessage.show({
        type: 'error',
        text: '1MB以上のファイルは登録できません。',
      })
      break
    case 422:
      // サーバ側でのバリデーションエラー
      const messages: string[] = []
      for (const [key, value] of Object.entries(e.response.data.errors)) {
        if (Array.isArray(value)) {
          value.forEach(v => messages.push(v))
        }
      }
      messages.forEach(message => {
        flashMessage.show({
          type: 'error',
          text: message,
        })
      })
      break
    case 500:
    case 503:
      flashMessage.show({
        type: 'error',
        text: 'エラーが発生しました。お手数ですが運営にお問い合わせください。',
      })
      break
    default:
      flashMessage.show({
        type: 'error',
        text: 'エラーが発生しました。お手数ですが再度実行してください。',
      })
      break
  }
}

export const httpService = {
  getLoginUser,
  signUp,
  login,
  sendPasswordResetMail,
  getPasswordReset,
  resetPassword,
  guestLogin,
  logout,
  updateUser,
  changePassword,
  withdraw,
  findCities,
  findSellingPoints,
  findCompanies,
  findCompanyById,
  toggleLike,
  getReserveHistory,
  getLikes,
  getMenus,
  getReserveInfo,
  getDaysOfWeek,
  reserve,
  cancelReserve,
  postContact,
}
