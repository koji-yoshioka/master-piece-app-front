<?php

use Illuminate\Database\Seeder;

class CompanyMenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('company_menus')->insert([
            [
                'company_id' => 1,
                'name' => 'Aコース',
                'comment' => 'コメント1',
                'price' => 50000,
                'minutes_length' => 60,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'name' => 'Bコース',
                'comment' => 'コメント2',
                'price' => 60000,
                'minutes_length' => 90,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'name' => 'Cコース',
                'comment' => 'コメント3',
                'price' => 70000,
                'minutes_length' => 120,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 2,
                'name' => 'B-Aコース',
                'comment' => 'コメント4',
                'price' => 50000,
                'minutes_length' => 180,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 2,
                'name' => 'B-Bコース',
                'comment' => 'コメント5',
                'price' => 60000,
                'minutes_length' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
