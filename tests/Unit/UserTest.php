<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;



class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * [GET]ユーザー情報取得テスト
     * 
     * 非正常系
     * テーブルに情報がない場合
     *
     * @return void
     * @test
     */
    public function 非正常系_ステータスコード404_user_get()
    {
        // ユーザー情報がない場合404を返す
        $response = $this->get("api/user?email=test@test.com");
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
        // usersテーブルにユーザー情報作成
        $user = [
            "name" => "test",
            "email" => "test@test.com",
            "password" => "testtest",
        ];
        DB::table('users')->insert($user);

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
     * 非正常系
     * リクエストパラメータがない場合
     * 
     * @return void
     * @test
     */
    public function 非正常系_ステータスコード500_user_post()
    {
        // リクエストパラメータなしの場合500を返す
        $response = $this->post("api/user");
        $response->assertStatus(500);
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
        // ユーザー情報作成
        $user = [
            "name" => "test",
            "email" => "test@test.com",
            "password" => "testtest",
        ];

        // リクエストパラメータありの場合200を返す
        $response = $this->post("api/user", $user);
        $response->assertStatus(200)->assertJsonFragment([
            "message" => "User created successfully"
        ]);
    }
}
