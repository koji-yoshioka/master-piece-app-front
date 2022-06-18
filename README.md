# MASTER_PIECE_APP

## アプリ概要
男性向けのファッションコーディネートサービスです。<br>
地域・価格から企業を検索し、予算にあったメニューを予約します。<br>
近年の男性の美意識の向上、および競合サービスが少なかったため、需要があるのではないかと思い作成しました。

<!-- <img src="/README_images/screen_shot.png"> -->

## URL
https://master-piece.site
- SPA
- 常時SSL化
- ゲストログイン可能
- レスポンシブデザイン対応

## 使用技術
- フロントエンド
  - HTML/CSS
  - Vue.js: 3.2.33
  - Vuex: 4.0.2
  - TypeScript: 4.6.4
  - Sass: 1.15.2
- バックエンド
  - PHP: 8.0.19
  - Rails: 6.20.44
- インフラ・開発環境
  - MySQL: 8.0.28
  - nginx
  - Docker/docker-compose
  - AWS(EC2・RDS・S3・ACM・VPC・Route53・ALB・CloudFront)
  - CircleCI（CI/CD）
- テスト・静的コード解析
  - PHPUnit

## 機能一覧
- 会員登録
- ログイン
- パスワードリセット
- 会員情報編集
- 退会
- 企業検索
- 企業詳細表示
- お気に入り登録
- お気に入り一覧
- メニュー選択
- メニュー予約
- 予約一覧
- 予約キャンセル
- マイページ
- お問い合わせ
- その他(プライバシーポリシー・情報セキュリティ方針・利用規約)

## インフラ構成図
<img src= '/README_images/infra.jpg' >

## ER図
<img src= '/README_images/ER.jpg' >

## 工夫した点
- サービス面
  - すぐに試して頂けるよう、ゲストログイン機能を用意しました。(退会等、一部の機能は利用できません。)
  - UI/UXを考慮し、機能が直感的に理解できるような文言やレイアウトを心がけました。
  - 本物さながらのWEBサービスを意識し、プライバシーポリシー等も作成しました。
- 技術面
  - 昨今のフロントエンドでは常識となりつつあるSPAで作成しました。
  - TypeScriptと相性がよいVue3で作成しました。
  - ユーザビリティを考慮し、サーバ側でのエラーはモーダルではなく、フラッシュメッセージで表示するようにしました。
  - 画像を大量に表示するアプリであるため、CDN(=CloudFront)による高速化を図りました。

## 改良予定
- 管理機能の追加(こちらはReactで開発する予定です。)
- 予約キャンセル時のメール送信機能を追加
- レビュー機能を追加(Vue3に対応した良いパッケージが無かった為、自作する必要があります。)
- Nuxtへの移行(開発段階でも検討はしていましたが、当時Nuxt3はβ版であった為見送っていました。)
- パスワードリセットメールのSMTPをGmailではなく独自ドメインのSMTPへ変更
- アニメーションや広告等を導入して、UI/UXを向上させます。
