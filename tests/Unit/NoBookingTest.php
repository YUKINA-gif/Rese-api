<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class NoBookingTest extends TestCase
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

        // ユーザーデータ作成
        $user = [
            "name" => "test",
            "email" => "test@test.com",
            "password" => Hash::make("testtest"),
        ];
        DB::table("users")->insert($user);
    }

    /**
     * [GET]ユーザー予約状況取得
     *
     * 非正常系
     * テーブルに情報がない場合
     * 
     * @return void
     * @test
     */
    public function 非正常系_ステータスコード404_user_booking()
    {
        // 予約がない場合404を返す
        $response = $this->get("api/user/1/booking");
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
        ]);
    }

    /**
     * [POST]予約登録
     *
     * 非正常系
     * リクエストパラメータがない場合
     * 
     * @return void
     * @test
     */
    public function 非正常系_ステータスコード500_booking_post()
    {
        $response = $this->post("api/booking");
        $response->assertStatus(500);
    }

    /**
     * [PUT]予約更新
     *
     * 非正常系
     * リクエストパラメータがない場合
     * 
     * @return void
     * @test
     */
    public function 非正常系_ステータスコード404_booking_put()
    {
        $response = $this->put("api/booking");
        $response->assertStatus(404);
    }

    /**
     * [DELETE]予約削除
     *
     * 非正常系
     * リクエストパラメータがない場合
     * 
     * @return void
     * @test
     */
    public function 非正常系_ステータスコード404_booking_delete_noParameter()
    {
        $response = $this->delete("api/booking");
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
        ]);
    }

    /**
     * [DELETE]予約削除
     *
     * 非正常系
     * 登録外のリクエストパラメータを送った場合
     * 
     * @return void
     * @test
     */
    public function 非正常系_ステータスコード404_booking_delete()
    {
        // データがない場合404を返す
        $booking = [
            "id" => "100",
            "user_id" => "100",
        ];
        $response = $this->delete("api/booking", $booking);
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
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
