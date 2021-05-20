<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Database\Seeders\BookingSeeder;

class BookingTest extends TestCase
{
    use RefreshDatabase;

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
        // ユーザー情報作成
        $user = [
            "name" => "test",
            "email" => "test@test.com",
            "password" => "testtest",
        ];
        DB::table("users")->insert($user);

        // 予約がない場合404を返す
        $response = $this->get("api/user/1/booking");
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
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
        // ユーザー情報作成
        $user = [
            "name" => "test",
            "email" => "test@test.com",
            "password" => "testtest",
        ];
        DB::table("users")->insert($user);

        // その他ランダムで予約情報作成
        $this->artisan("db:seed", ["--class" => BookingSeeder::class]);

        // 予約情報作成
        $booking = [
            "user_id" => "2",
            "store_id" => "1",
            "booking_date" => "2021/05/30",
            "booking_time" => "18:00",
            "booking_number" => "2",
        ];
        DB::table("bookings")->insert($booking);

        $booking = [
            "user_id" => "2",
            "store_id" => "4",
            "booking_date" => "2021/06/01",
            "booking_time" => "19:00",
            "booking_number" => "4",
        ];
        DB::table("bookings")->insert($booking);

        // 予約がある場合200を返す
        $response = $this->get("api/user/2/booking");
        $response->assertStatus(200)->assertJsonFragment([
            "user_id" => "2",
            "store_id" => "1",
            "booking_date" => "2021-05-30",
            "booking_time" => "18:00:00",
            "booking_number" => 2,
        ], [
            "user_id" => "2",
            "store_id" => "4",
            "booking_date" => "2021-06-01",
            "booking_time" => "19:00:00",
            "booking_number" => 4,
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
            "user_id" => "1",
            "store_id" => "1",
            "booking_date" => "2021/05/31",
            "booking_time" => "18:00",
            "booking_number" => 2
        ];
        $response = $this->post("api/booking", $booking);

        $response->assertStatus(200)->assertJsonFragment([
            "message" => "Booking successfully"
        ]);
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
        // 更新用予約データ作成
        $booking = [
            "user_id" => "1",
            "store_id" => "1",
            "booking_date" => "2021/05/30",
            "booking_time" => "18:00",
            "booking_number" => 2
        ];
        DB::table("bookings")->insert($booking);

        // 予約データ更新
        $booking_put = [
            "user_id" => "1",
            "store_id" => "1",
            "booking_date" => "2021/05/25",
            "booking_time" => "19:00",
            "booking_number" => 4
        ];
        $response = $this->put("api/booking", $booking_put);

        $response->assertStatus(200)->assertJsonFragment([
            "message" => "Booking updated successfully"
        ]);
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
        $response = $this->delete("api/booking",$booking);
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
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
        // 削除用予約データ作成
        $booking = [
            "user_id" => "1",
            "store_id" => "1",
            "booking_date" => "2021/05/30",
            "booking_time" => "18:00",
            "booking_number" => 2
        ];
        DB::table("bookings")->insert($booking);

        // 予約データがある場合200を返す
        $booking_delete = [
            "id" => "15",
            "user_id" => "1",
        ];
        $response = $this->delete("api/booking",$booking_delete);
        $response->assertStatus(200)->assertJsonFragment([
            "message" => "Booking deleted successfully"
        ]);
    }
}
