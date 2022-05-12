<?php

use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (['現金', 'クレジットカード', '交通系ICカード', 'PayPay'] as $paymentMethod) {
            DB::table('payment_methods')->insert([
                'name' => $paymentMethod,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
