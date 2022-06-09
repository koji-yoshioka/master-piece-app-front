<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => 'ゲスト太郎',
            'email' => 'guest-taro@test.com',
            'password' => Hash::make('password'),
            'is_guest' => true,
            'image_file_name' => 'guest.jpeg',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('users')->insert($param);
    }
}
