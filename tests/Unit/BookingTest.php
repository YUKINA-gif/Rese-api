<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Database\Seeders\BookingSeeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class BookingTest extends TestCase
{
    use RefreshDatabase;

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

        // ランダムで予約店舗登録
        $this->artisan("db:seed", ["--class" => BookingSeeder::class]);
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
            "user_id" => "1",
            "store_id" => "3",
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
