<?php

namespace Tests\Unit;

use Database\Seeders\BookingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Database\Seeders\FavoriteSeeder;



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

    /**
     * [GET]ユーザーお気に入り店舗取得
     *
     * 非正常系
     * テーブルに情報がない場合
     * 
     * @return void
     * @test
     */
    public function 非正常系_ステータスコード404_user_favorite()
    {
        // ユーザー情報作成
        $user = [
            "name" => "test",
            "email" => "test@test.com",
            "password" => "testtest",
        ];
        DB::table("users")->insert($user);

        // お気に入り店舗がない場合404を返す
        $response = $this->get("api/user/3/favorite");
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
        ]);
    }

    /**
     * [GET]ユーザーお気に入り店舗取得
     *
     * 正常系
     * テーブルに情報が1件ある場合
     * 
     * @return void
     * @test
     */
    public function 正常系_ステータスコード200_user_favorite()
    {
        // ユーザー情報作成
        $user = [
            "name" => "test",
            "email" => "test@test.com",
            "password" => "testtest",
        ];
        DB::table("users")->insert($user);

        // お気に入り店舗追加
        $favorite = [
            "user_id" => "4",
            "store_id" => "1",
        ];
        DB::table("favorites")->insert($favorite);

        // お気に入り店舗がある場合200を返す
        $response = $this->get("api/user/4/favorite");
        $response->assertStatus(200)->assertJsonFragment([
            "user_id" => "4",
            "store_id" => "1",
        ]);
    }

    /**
     * [GET]ユーザーお気に入り店舗取得
     *
     * 正常系
     * テーブルに情報が複数ある場合
     * 
     * @return void
     * @test
     */
    public function 正常系_ステータスコード200_user_favorites()
    {
        // ユーザー情報作成
        $user = [
            "name" => "test",
            "email" => "test@test.com",
            "password" => "testtest",
        ];
        DB::table("users")->insert($user);

        // お気に入り店舗追加
        $favorite = [
            "user_id" => "5",
            "store_id" => "1",
        ];
        DB::table("favorites")->insert($favorite);

        $favorite = [
            "user_id" => "5",
            "store_id" => "5",
        ];
        DB::table("favorites")->insert($favorite);

        // その他ランダムで情報も追加
        $this->artisan("db:seed", ["--class" => FavoriteSeeder::class]);

        // お気に入り店舗がある場合200を返す
        $response = $this->get("api/user/5/favorite");
        $response->assertStatus(200)->assertJsonFragment([
            "user_id" => "5",
            "store_id" => "1",
        ], [
            "user_id" => "5",
            "store_id" => "5",
        ]);
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
        // ユーザー情報作成
        $user = [
            "name" => "test",
            "email" => "test@test.com",
            "password" => "testtest",
        ];
        DB::table("users")->insert($user);

        // 予約がない場合404を返す
        $response = $this->get("api/user/6/booking");
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
        ]);
    }

    /**
     * [GET]ユーザー予約状況取得
     *
     * 正常系
     * テーブルに情報が1件ある場合
     * 
     * @return void
     * @test
     */
    public function 正常系_ステータスコード200_user_booking()
    {
        // ユーザー情報作成
        $user = [
            "name" => "test",
            "email" => "test@test.com",
            "password" => "testtest",
        ];
        DB::table("users")->insert($user);

        // 予約情報作成
        $booking = [
            "user_id" => "7",
            "store_id" => "1",
            "booking_date" => "2021/05/30",
            "booking_time" => "18:00",
            "booking_number" => "2",
        ];
        DB::table("bookings")->insert($booking);

        // 予約がある場合200を返す
        $response = $this->get("api/user/7/booking");
        $response->assertStatus(200)->assertJsonFragment([
            "user_id" => "7",
            "store_id" => "1",
            "booking_date" => "2021-05-30",
            "booking_time" => "18:00:00",
            "booking_number" => 2,
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
            "user_id" => "8",
            "store_id" => "1",
            "booking_date" => "2021/05/30",
            "booking_time" => "18:00",
            "booking_number" => "2",
        ];
        DB::table("bookings")->insert($booking);

        $booking = [
            "user_id" => "8",
            "store_id" => "4",
            "booking_date" => "2021/06/01",
            "booking_time" => "19:00",
            "booking_number" => "4",
        ];
        DB::table("bookings")->insert($booking);

        // 予約がある場合200を返す
        $response = $this->get("api/user/8/booking");
        $response->assertStatus(200)->assertJsonFragment([
            "user_id" => "8",
            "store_id" => "1",
            "booking_date" => "2021-05-30",
            "booking_time" => "18:00:00",
            "booking_number" => 2,
        ], [
            "user_id" => "8",
            "store_id" => "4",
            "booking_date" => "2021-06-01",
            "booking_time" => "19:00:00",
            "booking_number" => 4,
        ]);
    }
}
