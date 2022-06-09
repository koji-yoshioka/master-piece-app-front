<script setup lang="ts">
import { computed, ref } from 'vue'
import { useStore as useAuthStore } from '@/store/auth'
import { httpService } from '@/services/httpService'
import { useRouter } from 'vue-router'
import { useVuelidate } from '@vuelidate/core'
import { required, alphaNum, minLength, sameAs, email, helpers } from '@vuelidate/validators'
import Section from '@/components/Section.vue'
import TitleLabel from '@/components/TitleLabel.vue'
import InputEMail from '@/components/InputEMail.vue'
import InputText from '@/components/InputText.vue'
import InputPassword from '@/components/InputPassword.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'

// 認証情報
const authStore = useAuthStore()
// ルーティング情報
const router = useRouter()

// 入力したパスワード
const currentPassword = ref<string>('')
// サインアップ実行中フラグ
const signUping = ref<boolean>(false)

// --start バリデーション関連
const fields = ref({
  name: '',
  email: '',
  password: '',
  confirmPassword: '',
})
const rules = {
  name: {
    required: helpers.withMessage('ユーザ名を入力してください。', required)
  },
  email: {
    required: helpers.withMessage('メールアドレスを入力してください。', required),
    email: helpers.withMessage('メールアドレスの形式が不正です。', email)
  },
  password: {
    required: helpers.withMessage('パスワードを入力してください。', required),
    minLength: helpers.withMessage('パスワードは8文字以上の半角英数字で入力してください。', minLength(8)),
    alphaNum: helpers.withMessage('パスワードは8文字以上の半角英数字で入力してください。', alphaNum),
  },
  confirmPassword: {
    required: helpers.withMessage('パスワードを入力してください。', required),
    sameAsPassword: helpers.withMessage('同じパスワードを入力してください。', sameAs(currentPassword))
  }
}
const v$ = useVuelidate(rules, fields)
// --end

// 送信ボタンの活性制御
const isDisabled = computed(() =>
  (!v$.value.name.$model
    || !v$.value.email.$model
    || !v$.value.password.$model
    || !v$.value.confirmPassword.$model)
  || v$.value.$invalid
)

// 送信
const submit = async () => {
  signUping.value = true
  const userInfo = await httpService.signUp({
    name: v$.value.name.$model,
    email: v$.value.email.$model,
    password: v$.value.password.$model,
    password_confirmation: v$.value.confirmPassword.$model,
  })
  signUping.value = false
  authStore.dispatch('setUser', userInfo)
  if (userInfo) {
    router.push({ name: 'top' })
  }
}
</script>

<template>
  <div class="page-sign-up">
    <Section class="page-sign-up__form">
      <h2 class="page-sign-up__form-title">会員登録</h2>

      <div class="page-sign-up__input-area">
        <TitleLabel class="page-sign-up__input-label" :required="true">ユーザ名</TitleLabel>
        <InputText class="page-sign-up__input-input" :errors="v$.name.$errors" v-model="v$.name.$model">
        </InputText>
      </div>

      <div class="page-sign-up__input-area">
        <TitleLabel class="page-sign-up__input-label" :required="true">メールアドレス</TitleLabel>
        <InputEMail class="page-sign-up__input-input" :errors="v$.email.$errors" v-model="v$.email.$model">
        </InputEMail>
      </div>

      <div class="page-sign-up__input-area">
        <TitleLabel class="page-sign-up__input-label" :required="true">パスワード</TitleLabel>
        <InputPassword class="page-sign-up__input-input" :errors="v$.password.$errors"
          @update:modelValue="currentPassword = $event" v-model="v$.password.$model">
        </InputPassword>
      </div>

      <div class="page-sign-up__input-area">
        <TitleLabel class="page-sign-up__input-label" :required="true">パスワード（確認用）</TitleLabel>
        <InputPassword class="page-sign-up__input-input" :errors="v$.confirmPassword.$errors"
          v-model="v$.confirmPassword.$model">
        </InputPassword>
      </div>

      <div class="page-sign-up__submit-area">
        <PrimaryButton class="page-sign-up__submit" :disabled="isDisabled" @click="submit">送信</PrimaryButton>
      </div>

    </Section>
    <vue-element-loading class="page-sign-up__loading" :active="signUping" :background-color="'#1c1c1c'" :color="'#fff'"
      :is-full-screen="true" :spinner="'spinner'" :text="'登録処理を実行しています'" />
  </div>
</template>

<style lang="scss" scoped>
@use "~@/mixins";

.page-sign-up {

  // 画面全体を覆うよう、ヘッダとフッタより大きなz-indexを指定
  &::v-deep(.velmld-overlay) {
    z-index: 9999;
  }
}

.page-sign-up__form {
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

.page-sign-up__form-title {
  color: #dcc090;
  font-size: 1.5em;
  text-align: center;
}

.page-sign-up__input-area {
  width: 100%;
}

.page-sign-up__input-label {}

.page-sign-up__input-input {}

.page-sign-up__submit-area {
  display: flex;
  justify-content: flex-end;
  width: 100%;
}

.page-sign-up__submit {
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

.page-sign-up__loading {
  opacity: 0.8;
}
</style>
