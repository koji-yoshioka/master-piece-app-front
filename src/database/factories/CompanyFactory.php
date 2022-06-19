<?php

/** @var Factory $factory */

use App\Models\Company;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'name' => 'テスト株式会社',
        'tel' => '0364066000',
        'zip_code' => '1066108',
        'prefecture' => '東京都',
        'city' => '港区',
        'rest_address' => '六本木六本木ヒルズ森タワー',
        'nearest_station' => '東京メトロ日比谷線　六本木駅',
        'business_hours_from' => '0900',
        'business_hours_to' => '1800',
        'comment' => 'コメントテスト',
        'note' => '備考テスト',
        'created_at' => $faker->dateTime(),
        'updated_at' => $faker->dateTime(),
    ];
});
