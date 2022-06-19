<?php

namespace Tests\Feature;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyApiTest extends TestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // テストユーザー作成
        $this->user = factory(Company::class)->create();
    }

    public function testGetCompanySuccess()
    {
        $response = $this->json('GET', 'api/company/1');
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => 1
                , 'name' => 'テスト株式会社'
                , 'tel' => '0364066000'
                , 'zipCode' => '1066108'
                , 'prefecture' => '東京都'
                , 'city' => '港区'
                , 'restAddress' => '六本木六本木ヒルズ森タワー'
                , 'nearestStation' => '東京メトロ日比谷線　六本木駅'
                , 'businessHoursFrom' => '0900'
                , 'businessHoursTo' => '1800'
                , 'comment' => 'コメントテスト'
                , 'note' => '備考テスト'
                , 'logo' => null
                , 'images' => []
                , 'sellingPoints' => []
                , 'paymentMethods' => []
                , 'holidays' => []
                , 'userLike' => false
            ]);
    }

    public function testGetCompanyFail()
    {
        $response = $this->json('GET', 'api/company/2');
        $response->assertStatus(404);
    }
}
