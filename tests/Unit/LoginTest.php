<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 初期データ準備
     *
     * ユーザーデータ作成
     * 
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $user = new User;
        $user->fill([
            "name" => "test",
            "email" => "test@test.com",
            "password" => Hash::make("testtest"),
        ])->save();
    }

    /**
     * [POST]ログイン
     *
     * 非正常系
     * データ情報がない場合
     * 
     * @return void
     * @test
     */
    public function 非正常系_ステータスコード500_login_post_noDatabase()
    {
        $user = [
            "email" => "abc@def.com",
            "password" => "abcdefgh",
        ];
        $response = $this->post("api/login", $user);
        $response->assertStatus(500);
    }

    /**
     * [POST]ログイン
     *
     * 非正常系
     * リクエストパラメータがない場合
     * 
     * @return void
     * @test
     */
    public function 非正常系_ステータスコード500_login_post()
    {
        $response = $this->post("api/login");
        $response->assertStatus(500);
    }

    /**
     * [POST]ログイン
     *
     * 正常系
     * 
     * @return void
     * @test
     */
    public function 正常系_ステータスコード200_login_post()
    {
        $user_data = [
            "email" => "test@test.com",
            "password" => "testtest",
        ];
        $response = $this->post("api/login", $user_data);
        $response->assertStatus(200)->assertJsonFragment([
            "auth" => true
        ]);
    }

    /**
     * データリフレッシュ
     * 
     * @return void
     */
    public function tearDown(): void
    {
        Artisan::call('migrate:refresh');
        parent::tearDown();
    }
}
