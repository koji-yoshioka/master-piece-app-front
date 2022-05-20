<?php

use Illuminate\Database\Seeder;

class CompanyHolidayTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('company_holiday')->insert([
            'company_id' => 1,
            'day_of_week_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('company_holiday')->insert([
            'company_id' => 2,
            'day_of_week_id' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
