<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * ユーザーお気に入り店舗取得テスト
     *
     * @return void
     * @test
     */
    public function user_favorite()
    {
        $data = [
            "store_id" => "16"
        ];
        $response = $this->get('/api/user/1/favorite', $data);

        $response->assertStatus(200)->assertJsonFragment([
            "store_id" => "16",
            "user_id" => "1"
        ]);
    }
    /**
     * ユーザー予約情報取得テスト
     *
     * @return void
     * @test
     */
    public function user_booking()
    {
        $data = [
            "store_id" => "14"
        ];
        $response = $this->get("api/user/4/booking",$data);
        $response->assertOk()->assertJsonFragment([
            "store_id" => "14",
            "user_id" => "4"
        ]);
    }
}
