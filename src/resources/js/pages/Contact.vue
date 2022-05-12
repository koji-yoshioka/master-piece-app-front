<script setup lang="ts">
import { computed, ref } from 'vue'
import { useVuelidate } from '@vuelidate/core'
import { required, email, helpers } from '@vuelidate/validators'
import axios from 'axios'
import Section from '@/components/Section.vue'
import TitleLabel from '@/components/TitleLabel.vue'
import InputEMail from '@/components/InputEMail.vue'
import InputTextArea from '@/components/InputTextArea.vue'

const fields = ref({
  email: null,
  comment: null,
})
const rules = {
  email: { required: helpers.withMessage('メールアドレスを入力してください。', required), email: helpers.withMessage('メールアドレスの形式が不正です。', email) },
  comment: { required: helpers.withMessage('お問い合わせ内容を入力してください。', required) },
}
const v$ = useVuelidate(rules, fields)

const isActiveSubmit = computed(
  () => (v$.value.email.$model && v$.value.comment.$model)
    && !v$.value.$invalid
)

const submit = () => {
  if (v$.value.$invalid) {
    return;
  }
  console.log('submit')
}
</script>

<template>
  <div class="page-contact">
    <Section class="page-contact__form">

      <h2 class="page-profile__form-title">お問い合わせ</h2>

      <div class="page-contact__tel-area">
        <div class="page-contact__tel-label-area">
          <font-awesome-icon class="page-contact__tel-label-icon" :icon="['fas', 'square-caret-down']" size="2x" />
          <h3 class="page-contact__tel-label">お電話でのお問い合わせはコチラ</h3>
        </div>
        <p class="page-contact__tel-number">0120-999-9999</p>
      </div>

      <div class="page-contact__mail-area">
        <div class="page-contact__mail-label-area">
          <font-awesome-icon class="page-contact__mail-label-icon" :icon="['fas', 'square-caret-down']" size="2x" />
          <h3 class="page-contact__mail-label">メールでのお問い合わせはコチラ</h3>
        </div>

        <div class="page-contact__address-area">
          <TitleLabel class="page-contact__address-label" :required="true">メールアドレス</TitleLabel>
          <InputEMail class="page-contact__address-input" :errors="v$.email.$errors" v-model="v$.email.$model">
          </InputEMail>
        </div>

        <div class="page-contact__text-area">
          <TitleLabel class="page-contact__text-label" :required="true">お問い合わせ内容</TitleLabel>
          <InputTextArea class="page-contact__text-input" :rows="20" :errors="v$.comment.$errors"
            v-model="v$.comment.$model"></InputTextArea>
        </div>

      </div>

    </Section>

  </div>
</template>

<style lang="scss" scoped>
@use "~@/mixins";

.page-contact {}

.page-contact__form {
  margin: 0 auto;
  width: clamp(200px, 80%, 500px);
}

.page-profile__form-title {
  color: #dcc090;
  font-size: 1.5em;
  text-align: center;
}

.page-contact__tel-area {
  @include mixins.mq(sp) {
    margin-top: 30px;
  }

  @include mixins.mq(tablet) {
    margin-top: 45px;
  }

  @include mixins.mq(pc) {
    margin-top: 60px;
  }
}

.page-contact__tel-label-area {
  align-items: center;
  display: flex;
  gap: 10px;
}

.page-contact__tel-label-icon {
  color: #878990
}

.page-contact__tel-label {
  color: #fff;
}

.page-contact__tel-number {
  color: #dcc090;
  font-size: 1.5rem;
  margin-top: 20px;
}

.page-contact__mail-area {
  @include mixins.mq(sp) {
    margin-top: 30px;
  }

  @include mixins.mq(tablet) {
    margin-top: 45px;
  }

  @include mixins.mq(pc) {
    margin-top: 60px;
  }
}

.page-contact__mail-label-area {
  align-items: center;
  display: flex;
  gap: 10px;
}

.page-contact__mail-label-icon {
  color: #878990
}

.page-contact__mail-label {
  color: #fff;
}

.page-contact__address-area {
  margin-top: 20px;
}

.page-contact__text-area {
  @include mixins.mq(sp) {
    margin-top: 30px;
  }

  @include mixins.mq(tablet) {
    margin-top: 45px;
  }

  @include mixins.mq(pc) {
    margin-top: 60px;
  }
}

.page-contact__submit-area {
  @include mixins.mq(sp) {
    margin-top: 30px;
  }

  @include mixins.mq(tablet) {
    display: flex;
    justify-content: flex-end;
    margin-top: 45px;
  }

  @include mixins.mq(pc) {
    display: flex;
    justify-content: flex-end;
    margin-top: 60px;
  }
}

.page-contact__submit {

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
