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

const isDisabled = computed(() =>
  (!v$.value.name.$model
    || !v$.value.email.$model)
  || v$.value.$invalid
)

const submit = async () => {
  console.log('save profile')
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
        <PrimaryButton class="page-profile__submit" :disabled="isDisabled" @click="submit">保存</PrimaryButton>
      </div>
    </Section>
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
</style>
