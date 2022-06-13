<script setup lang="ts">
import Section from '@/components/Section.vue'
</script>

<template>
  <div class="page-security">
    <Section class="page-security__section">
      <h2 class="page-security__title">情報セキュリティ方針</h2>
      <div class="page-security__item">
        <h3 class="page-security__item-title">基本理念</h3>
        <p class="page-security__item-text">
          株式会社MASTER
          PIECE(以下、当社)は、昨今の情報セキュリティリスク増大に対応した当社の事業活動への影響を鑑み、活動において利用される全ての情報資産を適切に保護することを重要な経営課題と位置づけています。当社は、役員、従業員以下、すべての従業者を挙げて本方針を遵守し、情報資産の機密性、完全性、可用性といった情報セキュリティを維持するための活動を実践いたします。
        </p>
      </div>
      <div class="page-security__item">
        <h3 class="page-security__item-title">基本方針</h3>
        <ul class="page-security__item-list">
          <li>当社サービスの提供・運営のため</li>
          <li>ユーザーからのお問い合わせに回答するため（本人確認を行うことを含む）</li>
          <li>ユーザーが利用中のサービスの新機能，更新情報，キャンペーン等及び当社が提供する他のサービスの案内のメールを送付するため</li>
          <li>メンテナンス，重要なお知らせなど必要に応じたご連絡のため</li>
          <li>利用規約に違反したユーザーや，不正・不当な目的でサービスを利用しようとするユーザーの特定をし，ご利用をお断りするため</li>
          <li>ユーザーにご自身の登録情報の閲覧や変更，削除，ご利用状況の閲覧を行っていただくため</li>
          <li>有料サービスにおいて，ユーザーに利用料金を請求するため</li>
          <li>上記の利用目的に付随する目的</li>
        </ul>
      </div>

      <div class="page-security__item--sign">
        <p>制定・施行 : 2022年 6月 1日</p>
        <p>株式会社 MASTER PIECE</p>
        <p>代表取締役 芳岡 浩司</p>
      </div>

    </Section>
  </div>
</template>

<style lang="scss" scoped>
@use "~@/mixins";

.page-security__section {
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

.page-security__section {
  display: flex;
  flex-direction: column;
  margin: 0 auto;
  width: 100%;

  @include mixins.mq(sp) {
    max-width: 400px;
    row-gap: 30px;
  }

  @include mixins.mq(tablet) {
    max-width: 750px;
    row-gap: 40px;
  }

  @include mixins.mq(pc) {
    max-width: 1100px;
    row-gap: 50px;
  }
}

.page-security__title {
  color: #dcc090;
  font-size: 1.5em;
}

.page-security__item {}

.page-security__item-title {
  border-bottom: 2px solid #dcc090;
  color: #dcc090;
  font-size: 1.2em;
  padding: 10px 0;

  @include mixins.mq(sp) {
    margin-bottom: 10px;
  }

  @include mixins.mq(tablet) {
    margin-bottom: 20px;
  }

  @include mixins.mq(pc) {
    margin-bottom: 30px;
  }
}

.page-security__item-text {
  color: #fff;
  line-height: 1.8;
}

.page-security__item-list {
  list-style-type: decimal;
  margin-block-start: 1em;
  margin-block-end: 1em;
  margin-inline-start: 0px;
  margin-inline-end: 0px;
  padding-inline-start: 20px;
}

.page-security__item-list>li {
  color: #fff;
  line-height: 1.8;
  margin-bottom: 20px;

  &::marker {
    font-variant-numeric: tabular-nums;
    text-align: start;
    text-align-last: start;
    text-indent: 0px;
    text-transform: none;
    unicode-bidi: isolate;
  }
}

.page-security__item-list li:last-child {
  margin-bottom: 0;
}

.page-security__item--sign {
  align-items: flex-end;
  display: flex;
  flex-direction: column;
  gap: 10px
}

.page-security__item--sign p {
  color: #fff;
  line-height: 1.8;
}
</style>
