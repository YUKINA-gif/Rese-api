<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
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
     * [GET]ユーザー情報取得テスト
     * 
     * 異常系
     * テーブルに情報がない場合
     *
     * @return void
     * @test
     */
    public function 異常系_ステータスコード404_user_get()
    {
        // ユーザー情報がない場合404を返す
        $response = $this->get("api/user?email=abc@def.com");
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
        ]);
    }

    /**
     * [GET]ユーザー情報取得テスト
     * 
     * 正常系
     * テーブルに情報がある場合
     * 
     * @return void
     * @test
     */
    public function 正常系_ステータスコード200_user_get()
    {
        // ユーザー情報がある場合200を返す
        $response = $this->get("api/user?email=test@test.com");
        $response->assertStatus(200)->assertJsonFragment([
            "message" => "User got successfully",
            "name" => "test"
        ]);
    }

    /**
     * [POST]新規会員登録
     * 
     * 異常系
     * リクエストパラメータがない場合
     * 
     * @return void
     * @test
     */
    public function 異常系_ステータスコード302_user_post()
    {
        // リクエストパラメータなしの場合500を返す
        $response = $this->post("api/user");
        $response->assertStatus(302);
    }

    /**
     * [POST]新規会員登録
     * 
     * 正常系
     * リクエストパラメータがある場合
     * 
     * @return void
     * @test
     */
    public function 正常系_ステータスコード200_user_post()
    {
        // リクエストパラメータ
        $user = [
            "name" => "testname",
            "email" => "aaa@aaa.com",
            "password" => "aaaa1234",
        ];

        // リクエストパラメータありの場合200を返す
        $response = $this->post("api/user", $user);
        $response->assertStatus(200)->assertJsonFragment([
            "message" => "User created successfully"
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
