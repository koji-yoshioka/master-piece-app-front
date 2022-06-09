<?php

use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            [
                'name' => 'テスト1株式会社',
//                'email'      => 'test-company1@test.com',
//                'password'   => Hash::make('password123'),
                'tel' => '0364066000',
                'zip_code' => '1066108',
                'prefecture' => '東京都',
                'city' => '港区',
                'rest_address' => '六本木六本木ヒルズ森タワー',
                'nearest_station' => '東京メトロ日比谷線六本木駅',
                'business_hours_from' => '0900',
                'business_hours_to' => '1800',
                'comment' => 'コメント1です。',
                'note' => '備考1です。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'テスト2株式会社',
//                'email'      => 'test-company2@test.com',
//                'password'   => Hash::make('password123'),
                'tel' => '0364066000',
                'zip_code' => '1066108',
                'prefecture' => '東京都',
                'city' => '港区',
                'rest_address' => '六本木六本木ヒルズ森タワー',
                'nearest_station' => '東京メトロ日比谷線六本木駅',
                'business_hours_from' => '1000',
                'business_hours_to' => '1900',
                'comment' => 'コメント2です。',
                'note' => '備考2です。',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
