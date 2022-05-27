<script setup lang="ts">
import { computed, ref } from 'vue'
import { useStore } from '@/store/store'
import { useRouter } from 'vue-router'
import { useVuelidate } from '@vuelidate/core'
import { required, email, helpers } from '@vuelidate/validators'
import Section from '@/components/Section.vue'
import TitleLabel from '@/components/TitleLabel.vue'
import InputEMail from '@/components/InputEMail.vue'
import InputPassword from '@/components/InputPassword.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'

const currentPassword = ref<string>('')
const router = useRouter()
const store = useStore()

// ログイン実行中フラグ
const loggingIn = ref<boolean>(false)

const fields = ref({
  email: null,
  password: null,
})
const rules = {
  email: {
    required: helpers.withMessage('メールアドレスを入力してください。', required),
    email: helpers.withMessage('メールアドレスの形式が不正です。', email)
  },
  password: {
    required: helpers.withMessage('パスワードを入力してください。', required)
  },
}
const v$ = useVuelidate(rules, fields)

const isDisabled = computed(() =>
  (!v$.value.email.$model
    || !v$.value.password.$model)
  || v$.value.$invalid
)

const submit = async () => {
  loggingIn.value = true
  await store.dispatch('login', {
    email: v$.value.email.$model,
    password: v$.value.password.$model,
  })
  loggingIn.value = false
  if (!store.getters.hasError) {
    router.push('/')
  }
}

</script>

<template>
  <div class="page-login">
    <Section class="page-login__form">
      <h2 class="page-login__form-title">ログイン</h2>
      <div class="page-login__input-area">
        <TitleLabel class="page-login__input-label" :required="true">メールアドレス</TitleLabel>
        <InputEMail class="page-login__input-input" :errors="v$.email.$errors" v-model="v$.email.$model">
        </InputEMail>
      </div>
      <div class="page-login__input-area">
        <TitleLabel class="page-login__input-label" :required="true">パスワード</TitleLabel>
        <InputPassword class="page-login__input-input" :errors="v$.password.$errors"
          @update:modelValue="currentPassword = $event" v-model="v$.password.$model">
        </InputPassword>
      </div>
      <div class="page-login__submit-area">
        <PrimaryButton class="page-login__submit" :disabled="isDisabled" @click="submit">送信</PrimaryButton>
      </div>
    </Section>

    <vue-element-loading class="page-login__loading" :active="loggingIn" :background-color="'#1c1c1c'" :color="'#fff'"
      :is-full-screen="true" :spinner="'spinner'" :text="'ログイン処理を実行しています'" />
  </div>
</template>

<style lang="scss" scoped>
@use "~@/mixins";

.page-login {

  // 画面全体を覆うよう、ヘッダとフッタより大きなz-indexを指定
  &::v-deep(.velmld-overlay) {
    z-index: 9999;
  }
}

.page-login__form {
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

.page-login__form-title {
  color: #dcc090;
  font-size: 1.5em;
  text-align: center;
}

.page-login__input-area {
  width: 100%;
}

.page-login__input-label {}

.page-login__input-input {}

.page-login__submit-area {
  display: flex;
  justify-content: flex-end;
  width: 100%;
}

.page-login__submit {
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

.page-login__loading {
  opacity: 0.8;
}
</style>
