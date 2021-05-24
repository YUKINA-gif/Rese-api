<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\DatabaseSeeder;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 初期データ準備
     *
     * ユーザーデータ作成
     * 予約データ作成
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

        $user = [
            "name" => "test",
            "email" => "abcd@efgh.com",
            "password" => Hash::make("abcd1234"),
        ];
        DB::table("users")->insert($user);

        // ランダムでユーザーデータ20人分追加
        $this->artisan("db:seed", ["--class" => DatabaseSeeder::class]);

        // 予約情報作成
        $booking = [
            "user_id" => "1",
            "store_id" => "1",
            "booking_date" => "2021/05/30",
            "booking_time" => "18:00",
            "booking_number" => "2",
        ];
        DB::table("bookings")->insert($booking);

        $booking = [
            "user_id" => "1",
            "store_id" => "5",
            "booking_date" => "2021/06/01",
            "booking_time" => "19:00",
            "booking_number" => "4",
        ];
        DB::table("bookings")->insert($booking);

        $booking = [
            "user_id" => "2",
            "store_id" => "4",
            "booking_date" => "2021/06/03",
            "booking_time" => "18:30",
            "booking_number" => "3",
        ];
        DB::table("bookings")->insert($booking);

        // ランダムで予約データ10件追加
        $this->artisan("db:seed", ["--class" => DatabaseSeeder::class]);
    }

    /**
     * [GET]ユーザー予約状況取得
     *
     * 異常系
     * テーブルに情報がない場合
     * 
     * @return void
     * @test
     */
    public function 異常系_ステータスコード404_user_booking()
    {
        // 予約がない場合404を返す
        $response = $this->get("api/user/20/booking");
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
        ]);
    }

    /**
     * [POST]予約登録
     *
     * 異常系
     * リクエストパラメータがない場合
     * 
     * @return void
     * @test
     */
    public function 異常系_ステータスコード500_booking_post()
    {
        $response = $this->post("api/booking");
        $response->assertStatus(500);
    }

    /**
     * [PUT]予約更新
     *
     * 異常系
     * リクエストパラメータがない場合
     * 
     * @return void
     * @test
     */
    public function 異常系_ステータスコード404_booking_put()
    {
        $response = $this->put("api/booking");
        $response->assertStatus(404);
    }

    /**
     * [DELETE]予約削除
     *
     * 異常系
     * リクエストパラメータがない場合
     * 
     * @return void
     * @test
     */
    public function 異常系_ステータスコード404_booking_delete_noParameter()
    {
        $response = $this->delete("api/booking");
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
        ]);
    }

    /**
     * [DELETE]予約削除
     *
     * 異常系
     * 登録外のリクエストパラメータを送った場合
     * 
     * @return void
     * @test
     */
    public function 異常系_ステータスコード404_booking_delete()
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
     * [GET]ユーザー予約状況取得
     *
     * 正常系
     * テーブルに情報が1つだけある場合
     * 
     * @return void
     * @test
     */
    public function 正常系_ステータスコード200_user_booking()
    {
        // 予約がある場合200を返す
        $response = $this->get("api/user/2/booking");
        $response->assertStatus(200)->assertJsonFragment([
            "store_id" => "4",
            "user_id" => "2",
            "booking_date" => "2021-06-03",
            "booking_time" => "18:30:00",
            "booking_number" => 3,
        ]);
    }

    /**
     * [GET]ユーザー予約状況取得
     *
     * 正常系
     * テーブルに情報が複数ある場合
     * 
     * @return void
     * @test
     */
    public function 正常系_ステータスコード200_user_bookings()
    {
        // 予約がある場合200を返す
        $response = $this->get("api/user/1/booking");
        $response->assertStatus(200)->assertJsonFragment([
            "user_id" => "1",
            "store_id" => "1",
            "booking_date" => "2021-05-30",
            "booking_time" => "18:00:00",
            "booking_number" => 2,
        ], [
            "user_id" => "1",
            "store_id" => "5",
            "booking_date" => "2021-06-01",
            "booking_time" => "19:00:00",
            "booking_number" => 4,
        ]);
    }

    /**
     * [POST]予約登録
     *
     * 正常系
     * リクエストパラメータがある場合
     * 
     * @return void
     * @test
     */
    public function 正常系_ステータスコード200_booking_post()
    {
        // 予約登録
        $booking = [
            "booking_date" => "2021/05/31",
            "booking_time" => "18:00",
            "booking_number" => 2,
            "store_id" => "3",
            "user_id" => "1",
        ];
        $response = $this->post("api/booking", $booking);

        $response->assertStatus(200)->assertJsonFragment([
            "message" => "Booking successfully"
        ]);
    }

    /**
     * [PUT]予約更新
     *
     * 正常系
     * リクエストパラメータがある場合
     * 
     * @return void
     * @test
     */
    public function 正常系_ステータスコード200_booking_put()
    {
        // 予約データ更新
        $booking_put = [
            "user_id" => "1",
            "store_id" => "5",
            "booking_date" => "2021/06/03",
            "booking_time" => "19:30",
            "booking_number" => 6
        ];
        $response = $this->put("api/booking", $booking_put);

        $response->assertStatus(200)->assertJsonFragment([
            "message" => "Booking updated successfully"
        ]);
    }

    /**
     * [DELETE]予約削除
     *
     * 正常系
     * 
     * @return void
     * @test
     */
    public function 正常系_ステータスコード200_booking_delete()
    {
        // 予約データがある場合200を返す
        $booking_delete = [
            "id" => "1",
            "user_id" => "1",
        ];
        $response = $this->delete("api/booking", $booking_delete);
        $response->assertStatus(200)->assertJsonFragment([
            "message" => "Booking deleted successfully"
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
