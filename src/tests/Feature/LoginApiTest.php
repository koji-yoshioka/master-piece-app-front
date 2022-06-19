<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginApiTest extends TestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // テストユーザー作成
        $this->user = factory(User::class)->create();
    }

    public function testLoginSuccess()
    {
        $response = $this->json('POST', 'api/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson(['name' => $this->user->name]);

        $this->assertAuthenticatedAs($this->user);
    }

    public function testLoginFail()
    {
        $response = $this->json('POST', 'api/login', [
            'email' => $this->user->email,
            'password' => 'password12345',
        ]);

        $response
            ->assertStatus(422);
    }
}
