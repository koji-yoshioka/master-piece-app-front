<?php

use Illuminate\Database\Seeder;

class CompanySellingPointTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ([1, 2, 3] as $sellingPointId) {
            DB::table('company_selling_point')->insert([
                'company_id' => 1,
                'selling_point_id' => $sellingPointId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        foreach ([1, 3] as $sellingPointId) {
            DB::table('company_selling_point')->insert([
                'company_id' => 2,
                'selling_point_id' => $sellingPointId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
