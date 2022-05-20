<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
             UsersTableSeeder::class,
             CompaniesTableSeeder::class,
             RegionsTableSeeder::class,
             PrefecturesTableSeeder::class,
             CitiesTableSeeder::class,
             SellingPointsTableSeeder::class,
             PaymentMethodsTableSeeder::class,
             DaysOfWeekTableSeeder::class,
             CompanySellingPointTableSeeder::class,
             CompanyPaymentMethodTableSeeder::class,
             CompanyHolidayTableSeeder::class,
             CompanyMenuTableSeeder::class,
         ]);
    }
}
