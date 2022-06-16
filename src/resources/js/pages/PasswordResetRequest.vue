<script setup lang="ts">
import { computed, ref } from 'vue'
import { useStore as useAuthStore } from '@/store/auth'
import { httpService } from '@/services/httpService'
import { useRouter } from 'vue-router'
import { useVuelidate } from '@vuelidate/core'
import { required, email, helpers } from '@vuelidate/validators'
import Section from '@/components/Section.vue'
import TitleLabel from '@/components/TitleLabel.vue'
import InputEMail from '@/components/InputEMail.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'

// 送信中フラグ
const sendIng = ref<boolean>(false)

// 認証情報
const authStore = useAuthStore()
// ルーティング情報
const router = useRouter()

// --start バリデーション関連
const fields = ref({
  email: '',
})
const rules = {
  email: { required: helpers.withMessage('メールアドレスを入力してください。', required), email: helpers.withMessage('メールアドレスの形式が不正です。', email) },
}
const v$ = useVuelidate(rules, fields)
// --end

// 送信ボタンの活性制御
const isDisabled = computed(() =>
  !v$.value.email.$model
  || v$.value.$invalid
)

const submit = async () => {
  if (v$.value.$invalid) {
    return;
  }
  sendIng.value = true
  const isSuccess = await httpService.sendPasswordResetMail(v$.value.email.$model)
  sendIng.value = false
  // if (isSuccess) {
  //   router.push({ name: 'my-page' })
  // }
}
</script>

<template>
  <div class="page-password-reset-request">
    <Section class="page-password-reset-request__form">
      <h2 class="page-password-reset-request__form-title">パスワードリセット</h2>
      <div class="page-password-reset-request__input-area">
        <TitleLabel class="page-password-reset-request__input-label" :required="true">メールアドレス</TitleLabel>
        <InputEMail class="page-password-reset-request__input-input" :errors="v$.email.$errors"
          v-model="v$.email.$model">
        </InputEMail>
      </div>
      <div class="page-password-reset-request__submit-area">
        <PrimaryButton class="page-password-reset-request__submit" :disabled="isDisabled" @click="submit">リセットメール送信
        </PrimaryButton>
      </div>
    </Section>
    <vue-element-loading class="page-password-reset-request__loading" :active="sendIng" :background-color="'#1c1c1c'"
      :color="'#fff'" :is-full-screen="true" :spinner="'spinner'" :text="'送信中です'" />
  </div>
</template>

<style lang="scss" scoped>
@use "~@/mixins";

.page-password-reset-request {

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

.page-password-reset-request__form {
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

.page-password-reset-request__form-title {
  color: #dcc090;
  font-size: 1.5em;
  text-align: center;
}

.page-password-reset-request__input-area {
  width: 100%;
}

.page-password-reset-request__input-label {}

.page-password-reset-request__input-input {}

.page-password-reset-request__submit-area {
  display: flex;
  justify-content: flex-end;
  width: 100%;
}

.page-password-reset-request__submit {
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

.page-password-reset-request__submit-area {
  display: flex;
  justify-content: flex-end;
  width: 100%;
}

.page-password-reset-request__submit {
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

.page-password-reset-request__loading {
  opacity: 0.8;
}
</style>
