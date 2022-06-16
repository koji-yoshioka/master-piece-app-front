<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useStore as useAuthStore } from '@/store/auth'
import { httpService } from '@/services/httpService'
import { useRouter } from 'vue-router'
import { useVuelidate } from '@vuelidate/core'
import { required, alphaNum, minLength, sameAs, helpers } from '@vuelidate/validators'
import Section from '@/components/Section.vue'
import TitleLabel from '@/components/TitleLabel.vue'
import InputPassword from '@/components/InputPassword.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'

const props = defineProps({
  token: {
    type: String,
    required: true,
  },
})

// 認証情報
const authStore = useAuthStore()
// ルーティング情報
const router = useRouter()

// パスワードリセット取得済フラグ
const passwordResetLoaded = ref<boolean>(false)
// パスワードリセット有効フラグ
const isValidPasswordResetToken = ref<boolean>(false)
// パスワード更新フラグ
const isUpdating = ref<boolean>(false)

// アドレス
const email = ref<string>('')
// トークン
const token = ref<string>('')

// 入力した新パスワード
const inputNewPassword = ref<string>('')

// --start バリデーション関連
const fields = ref({
  password: '',
  confirmPassword: '',
})
const rules = {
  password: {
    required: helpers.withMessage('パスワードを入力してください。', required),
    minLength: helpers.withMessage('パスワードは8文字以上の半角英数字で入力してください。', minLength(8)),
    alphaNum: helpers.withMessage('パスワードは8文字以上の半角英数字で入力してください。', alphaNum),
  },
  confirmPassword: {
    required: helpers.withMessage('パスワードを入力してください。', required),
    sameAsPassword: helpers.withMessage('同じパスワードを入力してください。', sameAs(inputNewPassword))
  }
}
const v$ = useVuelidate(rules, fields)
// --end

// 送信ボタンの活性制御
const isDisabled = computed(() =>
  (!v$.value.password.$model
    || !v$.value.confirmPassword.$model)
  || v$.value.$invalid
)

const submit = async () => {
  if (v$.value.$invalid) {
    return;
  }
  isUpdating.value = true
  const isSuccess = await httpService.resetPassword({
    email: email.value,
    password: v$.value.password.$model,
    password_confirmation: v$.value.confirmPassword.$model,
    token: token.value,
  })
  isUpdating.value = false
  if (isSuccess) {
    router.push({ name: 'login' })
  }
}

onMounted(async () => {
  const resPasswordReset = await httpService.getPasswordReset(props.token)
  passwordResetLoaded.value = true
  if (!resPasswordReset) {
    return
  }
  isValidPasswordResetToken.value = true
  email.value = resPasswordReset.email
  token.value = resPasswordReset.token
})
</script>


<template>
  <div class="page-password-reset">
    <Section class="page-password-reset__form">
      <h2 class="page-password-reset__form-title">パスワードリセット</h2>

      <div v-show="!passwordResetLoaded" class="page-password-reset__loading-area">
        <vue-element-loading class="page-password-reset__loading" :active="true" :background-color="'#1c1c1c'"
          :color="'#fff'" :spinner="'spinner'" :text="'トークンをチェックしています。'" />
      </div>

      <template v-if="isValidPasswordResetToken">
        <div class="page-password-reset__input-area">
          <TitleLabel class="page-password-reset__input-label" :required="true">パスワード</TitleLabel>
          <InputPassword class="page-password-reset__input-input" :errors="v$.password.$errors"
            @update:modelValue="inputNewPassword = $event" v-model="v$.password.$model">
          </InputPassword>
        </div>
        <div class="page-password-reset__input-area">
          <TitleLabel class="page-password-reset__input-label" :required="true">パスワード（確認用）</TitleLabel>
          <InputPassword class="page-password-reset__input-input" :errors="v$.confirmPassword.$errors"
            v-model="v$.confirmPassword.$model">
          </InputPassword>
        </div>
        <div class="page-password-reset__submit-area">
          <PrimaryButton class="page-password-reset__submit" :disabled="isDisabled" @click="submit">送信</PrimaryButton>
        </div>
      </template>

    </Section>
    <vue-element-loading class="page-password-reset__loading" :active="isUpdating" :background-color="'#1c1c1c'"
      :color="'#fff'" :is-full-screen="true" :spinner="'spinner'" :text="'パスワードを変更しています'" />
  </div>
</template>

<style lang="scss" scoped>
@use "~@/mixins";

.page-password-reset {

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

.page-password-reset__form {
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

.page-password-reset__form-title {
  color: #dcc090;
  font-size: 1.5em;
  text-align: center;
}

.page-password-reset__loading-area {
  width: 100%;

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

.page-password-reset__loading {}

.page-password-reset__input-area {
  width: 100%;
}

.page-password-reset__input-label {}

.page-password-reset__input-input {}

.page-password-reset__submit-area {
  display: flex;
  justify-content: flex-end;
  width: 100%;
}

.page-password-reset__submit {
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

.page-password-reset__submit-area {
  display: flex;
  justify-content: flex-end;
  width: 100%;
}

.page-password-reset__submit {
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

.page-password-reset__loading {
  opacity: 0.8;
}
</style>
