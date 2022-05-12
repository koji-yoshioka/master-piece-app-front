<?php

use Illuminate\Database\Seeder;

class SellingPointsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (['ビジネス', 'プライベート', '駅チカ'] as $sellingPoint) {
            DB::table('selling_points')->insert([
                'name' => $sellingPoint,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
