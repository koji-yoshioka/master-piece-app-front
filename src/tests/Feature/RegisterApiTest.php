<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterApiTest extends TestCase
{

    use RefreshDatabase;

    public function testSignUp()
    {
        $data = [
            'name' => 'テスト一郎',
            'email' => 'test-ichiro@email.com',
            'password' => 'test12345',
            'password_confirmation' => 'test12345',
        ];

        $response = $this->json('POST', route('sign-up'), $data);

        $user = User::first();
        $this->assertEquals($data['name'], $user->name);

        $response
            ->assertStatus(201)
            ->assertJson(['name' => $user->name]);
    }
}
