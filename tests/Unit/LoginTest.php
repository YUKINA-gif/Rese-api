<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * ログイン
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
            "email" => "test@test.com",
            "password" => "testtest",
        ];
        $response = $this->post("api/login",$user);
        $response->assertStatus(500);
    }

    /**
     * ログイン
     *
     * 非正常系
     * リクエストパラメータがない場合
     * 
     * @return void
     * @test
     */
    public function 非正常系_ステータスコード500_login_post()
    {
        $user = [
            "name" => "test",
            "email" => "test@test.com",
            "password" => "testtest",
        ];
        DB::table("users")->insert($user);

        $response = $this->post("api/login");
        $response->assertStatus(500);
    }
}
