<script setup lang="ts">
import Section from '@/components/Section.vue'
</script>

<template>
  <div class="page-home">
    <div class="page-home__hero">
      <figure class="page-home__hero-image">
        <div class="page-home__hero-title-area">
          <p class="page-home__hero-title">「すべての男性を<span class="page-home__hero-title--strong">傑作</span>に」</p>
          <p class="page-home__hero-sub-title">男性専門のトータルコーディネートサービス</p>
        </div>
      </figure>
    </div>

    <Section class="page-home__suggestion">
      <p class="page-home__suggestion-question">こんな<span
          class="page-home__suggestion-question--strong">お悩み</span>ありませんか？</p>
      <div class="page-home__suggestion-case-area">
        <div class="page-home__suggestion-case">
          <font-awesome-icon class="page-home__suggestion-case-icon" :icon="['fas', 'square-check']" size="2x" />
          <p class="page-home__suggestion-case-text">初デートが決まったけれど、着ていく服がわからない。</p>
        </div>
        <div class="page-home__suggestion-case">
          <font-awesome-icon class="page-home__suggestion-case-icon" :icon="['fas', 'square-check']" size="2x" />
          <p class="page-home__suggestion-case-text">40代になったけれど、年相応の服装というものがわからない。</p>
        </div>
        <div class="page-home__suggestion-case">
          <font-awesome-icon class="page-home__suggestion-case-icon" :icon="['fas', 'square-check']" size="2x" />
          <p class="page-home__suggestion-case-text">とにかく時間がないから、早く決めたい。</p>
        </div>
        <div class="page-home__suggestion-case">
          <font-awesome-icon class="page-home__suggestion-case-icon" :icon="['fas', 'square-check']" size="2x" />
          <p class="page-home__suggestion-case-text">なんだかんだでファッションにはお金がかかるから、後悔したくない。</p>
        </div>
      </div>
      <div class="page-home__suggestion-answer-area">
        <p class="page-home__suggestion-answer-first">そのようなお悩みを</p>
        <p class="page-home__suggestion-answer-second"><span class="page-home__suggestion-answer-second--strong">MASTER
            PIECE</span>は解決します。</p>
      </div>
    </Section>

    <Section class="page-home__who-we-are">
      <div class="page-home__whe-we-are-image-area">
        <img class="page-home__whe-we-are-image" src="/images/who-we-are.jpg" alt="who-we-are">
      </div>
      <p class="page-home__who-we-are-title">WHO WE ARE</p>
      <p class="page-home__who-we-are-text">MASTER PIECEはお客様と、プロのコーディネーターを擁する企業様を繋げるマッチングサービスです。
        予算、年齢、エリア等、お客様の条件に合うコーディネーターをご指名ください。
        <br><br>
        いずれのコーディネーターも厳選させて頂いており、<span class="page-home__who-we-are-text--strong">その実力は折り紙付き</span>です。
      </p>
    </Section>

    <Section class="page-home__merit">
      <p class="page-home__merit-title">MERIT</p>
      <div class="page-home__merit-card-area">
        <div class="page-home__merit-card">
          <img class="page-home__merit-card-image" src="/images/professional.jpg" alt="professional">
          <p class="page-home__merit-card-title">１．プロが担当</p>
          <p class="page-home__merit-card-text">
            大手百貨店や有名ブランドの専属として数千名のコーディネートを担当したスタッフが多数在籍。<br>
            厳しい審査を通過した実績十分のスタッフ達が対応致します。<br>
            無料相談を実施している企業もございますので安心してご利用頂けます。
          </p>
        </div>

        <div class="page-home__merit-card">
          <img class="page-home__merit-card-image" src="/images/staff.jpg" alt="staff">
          <p class="page-home__merit-card-title">2．コーディネーターが豊富</p>
          <p class="page-home__merit-card-text">
            体型、人種、第一印象など、その人の持つ個性は様々です。<br>
            そして個性を最大限に活かす能力は一朝一夕で身に付くものではありません。<br>
            本サービスでは豊富な経験をもつスタッフのみが在籍しており、高い顧客満足度を誇っています。
          </p>
        </div>

        <div class="page-home__merit-card">
          <img class="page-home__merit-card-image" src="/images/time.jpg" alt="time">
          <p class="page-home__merit-card-title">3．最短１日で対応</p>
          <p class="page-home__merit-card-text">
            無料相談を受けてから数日後にサービスをご利用頂く方が大半ですが、２回目以降のご利用の方やお時間の関係で即日中にご利用頂きたい方にも対応しています。<br>
            即日対応でも料金は変わりません。<br>
            (一部の企業を除く)
          </p>
        </div>

        <div class="page-home__merit-card">
          <img class="page-home__merit-card-image" src="/images/reasonable.jpg" alt="reasonable">
          <p class="page-home__merit-card-title">4．安定したご利用料金</p>
          <p class="page-home__merit-card-text">
            平均として、ご利用頂くメニューの基本料金＋店頭での購入代金の５％〜１５％を利用料金として頂戴しております。<br>
            また、規定により返品が発生した場合はそのぶんの料金をお返しさせて頂きます。<br>
          </p>
        </div>
      </div>
    </Section>

    <Section class="page-home__flow">
      <p class="page-home__flow-title">FLOW</p>
      <div class="page-home__flow-card-area">

        <div class="page-home__flow-card">
          <div class="page-home__flow-card-image-area">
            <div class="page-home__flow-card-image-badge-area">
              <div class="page-home__flow-card-image-badge">
                <span class="page-home__flow-card-image-badge-text">STEP</span>
                <span class="page-home__flow-card-image-badge-text--strong">1</span>
              </div>
            </div>
            <img class="page-home__flow-card-image" src="/images/sign-up.jpg" alt="sign-up">
          </div>
          <div class="page-home__flow-card-text-area">
            <p class="page-home__flow-card-title">会員登録</p>
            <p class="page-home__flow-card-text">
              普段お使いのメールアドレスで会員登録を行います。<br>
              <br>
              ※画面上部のメニュー「会員登録」から実施頂けます。
            </p>
          </div>
        </div>

        <div class="page-home__flow-card">
          <div class="page-home__flow-card-image-area">
            <div class="page-home__flow-card-image-badge-area">
              <div class="page-home__flow-card-image-badge">
                <span class="page-home__flow-card-image-badge-text">STEP</span>
                <span class="page-home__flow-card-image-badge-text--strong">2</span>
              </div>
            </div>
            <img class="page-home__flow-card-image" src="/images/designate.jpg" alt="designate">
          </div>
          <div class="page-home__flow-card-text-area">
            <p class="page-home__flow-card-title">企業を選択</p>
            <p class="page-home__flow-card-text">
              地域やご予算の条件に合った企業を選択します。<br>
              <br>
              ※現状は東京都のみです。今後のサービス拡大にご期待ください。
            </p>
          </div>
        </div>

        <div class="page-home__flow-card">
          <div class="page-home__flow-card-image-area">
            <div class="page-home__flow-card-image-badge-area">
              <div class="page-home__flow-card-image-badge">
                <span class="page-home__flow-card-image-badge-text">STEP</span>
                <span class="page-home__flow-card-image-badge-text--strong">3</span>
              </div>
            </div>
            <img class="page-home__flow-card-image" src="/images/reserve.jpg" alt="reserve.jpg">
          </div>
          <div class="page-home__flow-card-text-area">
            <p class="page-home__flow-card-title">ご予約</p>
            <p class="page-home__flow-card-text">
              企業が提供する各プランからご利用するサービスを選んで頂き、予約します。
            </p>
          </div>
        </div>

        <div class="page-home__flow-card">
          <div class="page-home__flow-card-image-area">
            <div class="page-home__flow-card-image-badge-area">
              <div class="page-home__flow-card-image-badge">
                <span class="page-home__flow-card-image-badge-text">STEP</span>
                <span class="page-home__flow-card-image-badge-text--strong">4</span>
              </div>
            </div>
            <img class="page-home__flow-card-image" src="/images/payment.jpg" alt="payment">
          </div>
          <div class="page-home__flow-card-text-area">
            <p class="page-home__flow-card-title">お支払い</p>
            <p class="page-home__flow-card-text">
              当日にコーディネーター同伴でお買い物頂き、その後コーディネーターの所属企業へ料金をお支払い下さい。
            </p>
          </div>
        </div>

      </div>
    </Section>
  </div>
</template>

<style lang="scss" scoped>
@use "~@/mixins";

.page-home {
  @include mixins.mq(sp) {
    padding-left: 10px;
    padding-right: 10px;
  }

  @include mixins.mq(tablet) {
    padding-left: 15px;
    padding-right: 15px;
  }

  @include mixins.mq(pc) {
    padding-left: 20px;
    padding-right: 20px;
  }
}

.page-home__hero {
  margin: 0 auto;
  width: 100%;

  @include mixins.mq(sp) {
    max-width: 400px;
  }

  @include mixins.mq(tablet) {
    max-width: 750px;
  }

  @include mixins.mq(pc) {
    max-width: 1100px;
  }
}

.page-home__hero-image {
  background: url("/images/hero.png") #1c1c1c no-repeat center;
  height: 700px;
}

.page-home__hero-title-area {
  display: flex;
  flex-direction: column;
  line-height: 1.5;
  margin-top: 40px;
  row-gap: 10px;
}

.page-home__hero-title {
  color: #fff;
  font-size: 2rem;
}

.page-home__hero-title--strong {
  color: #dcc090;
}

.page-home__hero-sub-title {
  color: #fff;
}

.page-home__suggestion {
  align-items: center;
  display: flex;
  flex-direction: column;
  margin: 0 auto;
  max-width: 640px;

  @include mixins.mq(sp) {
    row-gap: 30px;
  }

  @include mixins.mq(tablet) {
    row-gap: 45px;
  }

  @include mixins.mq(pc) {
    row-gap: 60px;
  }
}

.page-home__suggestion-question {
  color: #fff;
  font-size: 1.5rem;
  line-height: 1.5;
  text-align: center;
}

.page-home__suggestion-question--strong {
  font-size: 2rem;
  font-weight: 700;
}

.page-home__suggestion-case-area {
  display: flex;
  flex-direction: column;
  gap: 30px;
}

.page-home__suggestion-case {
  align-items: center;
  display: flex;
  gap: 10px;
}

.page-home__suggestion-case-icon {
  color: #878990;
}

.page-home__suggestion-case-text {
  color: #fff;
  line-height: 1.5;
}

.page-home__suggestion-answer-area {
  line-height: 1.5;
  text-align: center;
  width: fit-content;
}

.page-home__suggestion-answer-first {
  color: #fff;
  font-size: 1.5rem;
}

.page-home__suggestion-answer-second {
  color: #fff;
  font-size: 1.5rem;
}

.page-home__suggestion-answer-second--strong {
  color: #dcc090;
  font-size: 2rem;
  font-weight: 700;
}

.page-home__who-we-are {
  align-items: center;
  display: flex;
  flex-direction: column;
  margin: 0 auto;
  max-width: 640px;
}

.page-home__whe-we-are-image-area {
  width: fit-content;
}

.page-home__whe-we-are-image {
  width: 100%;
  max-width: 640px;
}

.page-home__who-we-are-title {
  color: #dcc090;
  font-size: 2rem;
  font-weight: 700;
  line-height: 1.5;

  @include mixins.mq(sp) {
    padding: 40px 0;
  }

  @include mixins.mq(tablet) {
    padding: 50px 0;
  }

  @include mixins.mq(pc) {
    padding: 60px 0;
  }
}

.page-home__who-we-are-text {
  color: #fff;
  line-height: 1.5;
}

.page-home__who-we-are-text--strong {
  font-size: 1.5rem;
  font-weight: 700;
}

.page-home__merit {
  align-items: center;
  display: flex;
  flex-direction: column;
  margin: 0 auto;
  max-width: 680px;
}

.page-home__merit-title {
  color: #dcc090;
  font-size: 2rem;
  font-weight: 700;
  line-height: 1.5;

  @include mixins.mq(sp) {
    padding: 40px 0;
  }

  @include mixins.mq(tablet) {
    padding: 50px 0;
  }

  @include mixins.mq(pc) {
    padding: 60px 0;
  }
}

.page-home__merit-card-area {
  display: flex;
  flex-wrap: wrap;
  gap: 40px;
  justify-content: center;
}

.page-home__merit-card {
  display: flex;
  flex-direction: column;
  gap: 40px;

  @include mixins.mq(sp) {
    width: 100%;
  }

  @include mixins.mq(tablet) {
    width: calc((100% - 40px) / 2);
  }

  @include mixins.mq(pc) {
    width: calc((100% - 40px) / 2);
  }
}

.page-home__merit-card-image {
  width: 100%;
}

.page-home__merit-card-title {
  color: #dcc090;
  font-size: 1.2rem;
  font-weight: 700;
  line-height: 1.5;
  text-align: center;
}

.page-home__merit-card-text {
  color: #fff;
  line-height: 1.5;
}

.page-home__flow {
  align-items: center;
  display: flex;
  flex-direction: column;
  margin: 0 auto;
  max-width: 680px;
}

.page-home__flow-title {
  color: #dcc090;
  font-size: 2rem;
  font-weight: 700;
  line-height: 1.5;

  @include mixins.mq(sp) {
    padding: 40px 0 100px 0;
  }

  @include mixins.mq(tablet) {
    padding: 50px 0 110px 0;
  }

  @include mixins.mq(pc) {
    padding: 60px 0 120px 0;
  }
}

.page-home__flow-card-area {
  display: flex;
  flex-direction: column;
  gap: 120px;
}

.page-home__flow-card:nth-child(2n) {
  @include mixins.mq(sp) {}

  @include mixins.mq(tablet) {
    flex-direction: row-reverse;
  }

  @include mixins.mq(pc) {
    flex-direction: row-reverse;
  }
}

.page-home__flow-card {
  display: flex;
  gap: 40px;

  @include mixins.mq(sp) {
    flex-direction: column;
  }

  @include mixins.mq(tablet) {
    flex-direction: row;
  }

  @include mixins.mq(pc) {
    flex-direction: row;
  }
}

.page-home__flow-card-image-area {
  position: relative;

  @include mixins.mq(sp) {
    width: 100%;
  }

  @include mixins.mq(tablet) {
    width: calc((100% - 40px) / 2);
  }

  @include mixins.mq(pc) {
    width: calc((100% - 40px) / 2);
  }
}

.page-home__flow-card-image-badge-area {
  align-items: center;
  background-color: #1c1c1c;
  border: 2px solid #dcc090;
  border-radius: 50%;
  display: flex;
  height: 80px;
  justify-content: center;
  left: 50%;
  position: absolute;
  top: -40px;
  transform: translateX(-50%);
  width: 80px;
}

.page-home__flow-card-image-badge {
  display: flex;
  flex-direction: column;
  row-gap: 5px;
  text-align: center;
}

.page-home__flow-card-image-badge-text {
  color: #dcc090;
  font-size: 18px;
}

.page-home__flow-card-image-badge-text--strong {
  color: #dcc090;
  font-size: 24px;
  font-weight: 700;
}

.page-home__flow-card-image {
  border: 2px solid #dcc090;
  width: 100%;
}

.page-home__flow-card-text-area {
  align-items: center;
  display: flex;
  flex-direction: column;
  gap: 40px;

  @include mixins.mq(sp) {
    width: 100%;
  }

  @include mixins.mq(tablet) {
    width: calc((100% - 40px) / 2);
  }

  @include mixins.mq(pc) {
    width: calc((100% - 40px) / 2);
  }
}

.page-home__flow-card-title {
  color: #dcc090;
  font-size: 1.5rem;
  font-weight: 700;
}

.page-home__flow-card-text {
  color: #fff;
  line-height: 1.5;
}
</style>
