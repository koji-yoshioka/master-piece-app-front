<script setup lang="ts">
import { computed, ref } from 'vue'
import { useStore } from '@/store/store'
import { useRouter } from 'vue-router'
import { useVuelidate } from '@vuelidate/core'
import { required, email, helpers } from '@vuelidate/validators'
import Section from '@/components/Section.vue'
import TitleLabel from '@/components/TitleLabel.vue'
import InputEMail from '@/components/InputEMail.vue'
import InputText from '@/components/InputText.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'

// グローバル情報
const store = useStore()
// ルーティング情報
const router = useRouter()

// 更新実行中フラグ
const isUpdating = ref<boolean>(false)

// --start バリデーション関連
const fields = ref({
  name: null,
  email: null,
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

// 送信ボタンの活性制御
const isDisabled = computed(() =>
  (!v$.value.name.$model
    || !v$.value.email.$model)
  || v$.value.$invalid
)

// 送信
const submit = async () => {
  isUpdating.value = true
  console.log('save profile')
  isUpdating.value = false
}

</script>

<template>
  <div class="page-profile">
    <Section class="page-profile__form">
      <h2 class="page-profile__form-title">プロフィール編集</h2>
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
      :color="'#fff'" :is-full-screen="true" :spinner="'spinner'" :text="'ユーザ情報を更新しています'" />
  </div>
</template>

<style lang="scss" scoped>
@use "~@/mixins";

.page-profile {
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
