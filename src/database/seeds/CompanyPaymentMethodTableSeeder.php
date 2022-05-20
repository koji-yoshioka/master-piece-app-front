<?php

use Illuminate\Database\Seeder;

class CompanyPaymentMethodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ([1, 2, 3] as $paymentMethodId) {
            DB::table('company_payment_method')->insert([
                'company_id' => 1,
                'payment_method_id' => $paymentMethodId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        foreach ([1, 2, 3, 4] as $paymentMethodId) {
            DB::table('company_payment_method')->insert([
                'company_id' => 2,
                'payment_method_id' => $paymentMethodId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
