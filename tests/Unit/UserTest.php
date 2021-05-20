<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ユーザー情報取得テスト
     *
     * @return void
     * @test
     */
    public function user_get()
    {
        // ユーザー情報がない場合404を返す
        $response = $this->get("api/user?email=test@test.com");
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
        ]);

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
     * 新規会員登録
     *
     * @return void
     * @test
     */
    public function user_post()
    {
        $user = [
            "name" => "test",
            "email" => "test@test.com",
            "password" => "testtest",
        ];

        $response = $this->post("api/user", $user);
        $response->assertStatus(200)->assertJsonFragment([
            "message" => "User created successfully"
        ]);
    }

    /**
     * ユーザーお気に入り店舗取得
     *
     * @return void
     * @test
     */
    public function user_favorite()
    {
        // usersテーブルにレコード追加
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

        // favoritesテーブルにレコード追加
        $favorite = [
            "user_id" => "3",
            "store_id" => "1",
        ];
        DB::table("favorites")->insert($favorite);

        // お気に入り店舗がある場合200を返す
        $response = $this->get("api/user/3/favorite");
        $response->assertStatus(200)->assertJsonFragment([
            "user_id" => "3",
            "store_id" => "1",
        ]);
    }

    /**
     * ユーザー予約状況取得
     *
     * @return void
     * @test
     */
    public function user_booking()
    {
        // usersテーブルにレコード追加
        $user = [
            "name" => "test",
            "email" => "test@test.com",
            "password" => "testtest",
        ];
        DB::table("users")->insert($user);

        // 予約がない場合404を返す
        $response = $this->get("api/user/4/booking");
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
        ]);

        // bookingsテーブルにレコード追加
        $booking = [
            "user_id" => "4",
            "store_id" => "1",
            "booking_date" => "2021/05/30",
            "booking_time" => "18:00",
            "booking_number" => "2",
        ];
        DB::table("bookings")->insert($booking);

        // 予約がある場合200を返す
        $response = $this->get("api/user/4/booking");
        $response->assertStatus(200)->assertJsonFragment([
            "user_id" => "4",
            "store_id" => "1",
            "booking_date" => "2021-05-30",
            "booking_time" => "18:00:00",
            "booking_number" => 2,
        ]);
    }
}
