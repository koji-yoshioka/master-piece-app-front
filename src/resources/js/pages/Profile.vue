<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useStore as useAuthStore } from '@/store/auth'
import { httpService } from '@/services/httpService'
import { useRouter } from 'vue-router'
import { useVuelidate } from '@vuelidate/core'
import { required, email, helpers } from '@vuelidate/validators'
import { UserImage } from '@/typings/interfaces/profile'
import { flashMessage } from '@smartweb/vue-flash-message'
import Section from '@/components/Section.vue'
import TitleLabel from '@/components/TitleLabel.vue'
import InputCircleImage from '@/components/InputCircleImage.vue'
import InputEMail from '@/components/InputEMail.vue'
import InputText from '@/components/InputText.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'

// 認証情報
const authStore = useAuthStore()
// ルーティング情報
const router = useRouter()
// 更新実行中フラグ
const isUpdating = ref<boolean>(false)

// ユーザイメージ
const userImage = ref<UserImage>({
  image: null,
  preview: null,
  isChanged: false,
})

// --start バリデーション関連
const fields = ref({
  name: '',
  email: '',
})
const rules = {
  name: {
    required: helpers.withMessage('ユーザ名を入力してください。', required)
  },
  email: {
    required: helpers.withMessage('メールアドレスを入力してください。', required),
    email: helpers.withMessage('メールアドレスの形式が不正です。', email)
  },
}
const v$ = useVuelidate(rules, fields)
// --end

// 画像選択
const imageChange = (image: File) => {
  const reader = new FileReader()
  reader.onload = onloadEvent => {
    userImage.value.preview = onloadEvent.target ? onloadEvent.target.result : null
  }
  reader.readAsDataURL(image)
  userImage.value.image = image
  userImage.value.isChanged = true
}

// 画像削除
const imageDelete = () => {
  userImage.value.image = null
  userImage.value.preview = null
  userImage.value.isChanged = true
}

// 送信ボタンの活性制御
const isDisabled = computed(() =>
  (!v$.value.name.$model
    || !v$.value.email.$model)
  || v$.value.$invalid
)

// 送信
const submit = async () => {
  if (v$.value.$invalid) {
    return;
  }
  isUpdating.value = true
  const formData = new FormData()
  if (userImage.value.isChanged) {
    formData.append('image', userImage.value.image ? userImage.value.image : '')
  }
  if (v$.value.name.$model && typeof v$.value.name.$model === 'string') {
    formData.append('name', v$.value.name.$model)
  }
  if (v$.value.email.$model && typeof v$.value.email.$model === 'string') {
    formData.append('email', v$.value.email.$model)
  }
  const isUpdateSuccess = await httpService.updateUser(formData)
  let isGetSuccess = false
  if (isUpdateSuccess) {
    const userInfo = await httpService.getLoginUser()
    authStore.dispatch('setUser', userInfo)
    isGetSuccess = !!userInfo
  }
  isUpdating.value = false
  if (isUpdateSuccess && isGetSuccess) {
    flashMessage.show({
      type: 'success',
      text: 'プロフィールが更新されました。',
    })
    router.push({ name: 'my-page' })
  }
}

onMounted(async () => {
  v$.value.name.$model = authStore.getters.loginUser.name
  v$.value.email.$model = authStore.getters.loginUser.email
  userImage.value.preview = authStore.getters.loginUser.imageFileName
    ? `https://static.master-piece.site/users/${authStore.getters.loginUser.id}/${authStore.getters.loginUser.imageFileName}`
    : null
})
</script>

<template>
  <div class="page-profile">
    <Section class="page-profile__form">
      <h2 class="page-profile__form-title">プロフィール編集</h2>
      <div class="page-profile__input-area">
        <InputCircleImage class="page-profile__input-input--image"
          :accept="['image/jpeg', 'image/png', 'image/svg+xml']" :preview="userImage.preview" @upload="imageChange"
          @fileDelete="imageDelete">イメージ</InputCircleImage>
      </div>
      <div class="page-profile__input-area">
        <TitleLabel class="page-profile__input-label" :required="true">ユーザ名</TitleLabel>
        <InputText class="page-profile__input-input" :errors="v$.name.$errors" v-model="v$.name.$model">
        </InputText>
      </div>
      <div class="page-profile__input-area">
        <TitleLabel class="page-profile__input-label" :required="true">メールアドレス</TitleLabel>
        <InputEMail class="page-profile__input-input" :errors="v$.email.$errors" v-model="v$.email.$model">
        </InputEMail>
      </div>
      <div class="page-profile__submit-area">
        <PrimaryButton class="page-profile__submit" :disabled="isDisabled" @click="submit">送信</PrimaryButton>
      </div>
    </Section>
    <vue-element-loading class="page-profile__loading" :active="isUpdating" :background-color="'#1c1c1c'"
      :color="'#fff'" :is-full-screen="true" :spinner="'spinner'" :text="'プロフィールを更新しています'" />
  </div>
</template>

<style lang="scss" scoped>
@use "~@/mixins";

.page-profile {

  // 画面全体を覆うよう、ヘッダとフッタより大きなz-indexを指定
  &::v-deep(.velmld-overlay) {
    z-index: 9999;
  }

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

.page-profile__form {
  align-items: center;
  display: flex;
  flex-direction: column;
  margin: 0 auto;
  width: clamp(200px, 80%, 500px);

  @include mixins.mq(sp) {
    row-gap: 30px;
  }

  @include mixins.mq(tablet) {
    row-gap: 40px;
  }

  @include mixins.mq(pc) {
    row-gap: 50px;
  }
}

.page-profile__form-title {
  color: #dcc090;
  font-size: 1.5em;
  text-align: center;
}

.page-profile__input-area {
  width: 100%;
}

.page-profile__input-label {}

.page-profile__input-input {}

.page-profile__input-input--image {
  margin: 0 auto;
  width: clamp(200px, 50%, 300px);
}

.page-profile__submit-area {
  display: flex;
  justify-content: flex-end;
  width: 100%;
}

.page-profile__submit {
  @include mixins.mq(sp) {
    width: 100%;
  }

  @include mixins.mq(tablet) {
    width: 50%;
  }

  @include mixins.mq(pc) {
    width: 50%;
  }
}

.page-profile__loading {
  opacity: 0.8;
}
</style>
