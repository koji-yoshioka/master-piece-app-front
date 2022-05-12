<?php

use Illuminate\Database\Seeder;

class PrefecturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('prefectures')->insert([
            ['name' => '北海道', 'region_id' => 1, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '青森県', 'region_id' => 1, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '岩手県', 'region_id' => 1, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '宮城県', 'region_id' => 1, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '秋田県', 'region_id' => 1, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '山形県', 'region_id' => 1, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '福島県', 'region_id' => 1, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '茨城県', 'region_id' => 2, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '栃木県', 'region_id' => 2, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '群馬県', 'region_id' => 2, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '埼玉県', 'region_id' => 2, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '千葉県', 'region_id' => 2, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '東京都', 'region_id' => 2, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '神奈川県', 'region_id' => 2, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '新潟県', 'region_id' => 3, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '富山県', 'region_id' => 3, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '石川県', 'region_id' => 3, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '福井県', 'region_id' => 3, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '山梨県', 'region_id' => 3, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '長野県', 'region_id' => 3, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '岐阜県', 'region_id' => 3, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '静岡県', 'region_id' => 3, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '愛知県', 'region_id' => 3, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '三重県', 'region_id' => 4, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '滋賀県', 'region_id' => 4, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '京都府', 'region_id' => 4, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '大阪府', 'region_id' => 4, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '兵庫県', 'region_id' => 4, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '奈良県', 'region_id' => 4, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '和歌山県', 'region_id' => 4, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '鳥取県', 'region_id' => 5, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '島根県', 'region_id' => 5, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '岡山県', 'region_id' => 5, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '広島県', 'region_id' => 5, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '山口県', 'region_id' => 5, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '徳島県', 'region_id' => 6, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '香川県', 'region_id' => 6, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '愛媛県', 'region_id' => 6, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '高知県', 'region_id' => 6, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '福岡県', 'region_id' => 7, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '佐賀県', 'region_id' => 7, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '長崎県', 'region_id' => 7, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '熊本県', 'region_id' => 7, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '大分県', 'region_id' => 7, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '宮崎県', 'region_id' => 7, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '鹿児島県', 'region_id' => 7, 'created_at' => now(), 'updated_at' => now()]
            ,['name' => '沖縄県', 'region_id' => 7, 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
