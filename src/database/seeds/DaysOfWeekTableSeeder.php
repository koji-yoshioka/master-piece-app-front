<?php

use Illuminate\Database\Seeder;

class DaysOfWeekTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'] as $dayOfweek) {
            DB::table('days_of_week')->insert([
                'name' => $dayOfweek,
                'abbreviation' => str_replace('曜日', '', $dayOfweek),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
