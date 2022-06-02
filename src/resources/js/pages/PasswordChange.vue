<script setup lang="ts">
import { computed, ref } from 'vue'
import { useStore } from '@/store/store'
import { useRouter } from 'vue-router'
import { useVuelidate } from '@vuelidate/core'
import { required, alphaNum, minLength, sameAs, helpers } from '@vuelidate/validators'
import { OK } from '@/util'
import axios, { AxiosResponse } from 'axios'
import Section from '@/components/Section.vue'
import TitleLabel from '@/components/TitleLabel.vue'
import InputText from '@/components/InputText.vue'
import InputPassword from '@/components/InputPassword.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'

// グローバル情報
const store = useStore()
// ルーティング情報
const router = useRouter()

// パスワード更新フラグ
const isUpdating = ref<boolean>(false)

// 入力した新パスワード
const inputNewPassword = ref<string>('')

// --start バリデーション関連
const fields = ref({
  currentPassword: null,
  newPassword: null,
  confirmNewPassword: null,
})
const rules = {
  currentPassword: {
    required: helpers.withMessage('パスワードを入力してください。', required),
  },
  newPassword: {
    required: helpers.withMessage('パスワードを入力してください。', required),
    minLength: helpers.withMessage('パスワードは8文字以上の半角英数字で入力してください。', minLength(8)),
    alphaNum: helpers.withMessage('パスワードは8文字以上の半角英数字で入力してください。', alphaNum),
  },
  confirmNewPassword: {
    required: helpers.withMessage('パスワードを入力してください。', required),
    sameAsPassword: helpers.withMessage('同じパスワードを入力してください。', sameAs(inputNewPassword))
  }
}
const v$ = useVuelidate(rules, fields)
// --end

// 送信ボタンの活性制御
const isDisabled = computed(() =>
  (!v$.value.currentPassword.$model
    || !v$.value.newPassword.$model
    || !v$.value.confirmNewPassword.$model)
  || v$.value.$invalid
)

const submit = async () => {
  if (v$.value.$invalid) {
    return;
  }
  isUpdating.value = true
  console.log('change password')
  const response = await axios.post('/api/password-change', {
    currentPassword: v$.value.currentPassword.$model,
    newPassword: v$.value.newPassword.$model,
    newPassword_confirmation: v$.value.confirmNewPassword.$model,
  }).catch(e => e.response || e)
  isUpdating.value = false
  if (response.status !== OK) {
    store.dispatch('setError', response)
  }
}

</script>

<template>
  <div class="page-password-change">
    <Section class="page-password-change__form">
      <h2 class="page-password-change__form-title">パスワード変更</h2>
      <div class="page-password-change__input-area">
        <TitleLabel class="page-password-change__input-label" :required="true">現在のパスワード</TitleLabel>
        <InputPassword class="page-password-change__input-input" :errors="v$.currentPassword.$errors"
          v-model="v$.currentPassword.$model">
        </InputPassword>
      </div>
      <div class="page-password-change__input-area">
        <TitleLabel class="page-password-change__input-label" :required="true">新しいパスワード</TitleLabel>
        <InputPassword class="page-password-change__input-input" :errors="v$.newPassword.$errors"
          @update:modelValue="inputNewPassword = $event" v-model="v$.newPassword.$model">
        </InputPassword>
      </div>
      <div class="page-password-change__input-area">
        <TitleLabel class="page-password-change__input-label" :required="true">新しいパスワード（確認用）</TitleLabel>
        <InputPassword class="page-password-change__input-input" :errors="v$.confirmNewPassword.$errors"
          v-model="v$.confirmNewPassword.$model">
        </InputPassword>
      </div>
      <div class="page-password-change__submit-area">
        <PrimaryButton class="page-password-change__submit" :disabled="isDisabled" @click="submit">送信</PrimaryButton>
      </div>
    </Section>
    <vue-element-loading class="page-password-change__loading" :active="isUpdating" :background-color="'#1c1c1c'"
      :color="'#fff'" :is-full-screen="true" :spinner="'spinner'" :text="'パスワードを変更しています'" />
  </div>
</template>

<style lang="scss" scoped>
@use "~@/mixins";

.page-password-change {
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

.page-password-change__form {
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

.page-password-change__form-title {
  color: #dcc090;
  font-size: 1.5em;
  text-align: center;
}

.page-password-change__input-area {
  width: 100%;
}

.page-password-change__input-label {}

.page-password-change__input-input {}

.page-password-change__submit-area {
  display: flex;
  justify-content: flex-end;
  width: 100%;
}

.page-password-change__submit {
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

.page-password-change__submit-area {
  display: flex;
  justify-content: flex-end;
  width: 100%;
}

.page-password-change__submit {
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
