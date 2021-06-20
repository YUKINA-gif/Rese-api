<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StoreManagerTest extends TestCase
{
    use DatabaseMigrations;

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

        $manager = [
            "login_id" => "01",
            "password" => Hash::make("Pass1234"),
        ];
        DB::table("store_managers")->insert($manager);
    }

    /**
     * [POST]ログイン
     *
     * 異常系
     * データ情報がない場合
     * 
     * @return void
     * @test
     */
    public function 異常系_ステータスコード500_login_post_noDatabase()
    {
        $manager = [
            "login_id" => "abcdef12",
            "password" => "abcdefgh",
        ];
        $response = $this->post("api/manage/storeManager/login", $manager);
        $response->assertStatus(500);
    }

    /**
     * [POST]ログイン
     *
     * 異常系
     * リクエストパラメータがない場合
     * 
     * @return void
     * @test
     */
    public function 異常系_ステータスコード302_login_post()
    {
        $response = $this->post("api/manage/storeManager/login");
        $response->assertStatus(302);
    }

    /**
     * [POST]パスワード発行
     *
     * 異常系
     * リクエストパラメータがない場合
     * 
     * @return void
     * @test
     */
    public function 異常系_ステータスコード302_create_pass_post()
    {
        $response = $this->post("api/manage/storeManager/create");
        $response->assertStatus(302);
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
        $manager = [
            "login_id" => "01",
            "password" => "Pass1234",
        ];
        $response = $this->post("api/manage/storeManager/login", $manager);
        $response->assertStatus(200)->assertJsonFragment([
            "auth" => true
        ]);
    }

    /**
     * [POST]パスワード発行
     *
     * 正常系
     * 
     * @return void
     * @test
     */
    public function 正常系_ステータスコード200_create_pass_post()
    {
        $login_id = [
            "login_id" => "159"
        ];
        $response = $this->post("api/manage/storeManager/create", $login_id);
        $response->assertStatus(200)->assertJsonFragment([
            "message" => "Store manager created successfully"
        ]);
    }

    /**
     * データリフレッシュ
     * 
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }
}
