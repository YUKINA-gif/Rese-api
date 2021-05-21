<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoStoresTest extends TestCase
{
    use RefreshDatabase;

    /**
     * [GET]店舗全データ取得
     *
     * 非正常系
     * データ情報がない場合
     * 
     * @return void
     * @test
     */
    public function 非正常系_ステータスコード404_stores_get()
    {
        // 店舗データがない場合は404を返す
        $response = $this->get("api/stores");
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
        ]);
    }

    /**
     * [GET]指定店舗データ取得
     *
     * 非正常系
     * データ情報がない場合
     * 
     * @return void
     * @test
     */
    public function 非正常系_ステータスコード404_store_get()
    {
        // 店舗データがない場合は404を返す
        $response = $this->get("api/stores/150");
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
        ]);
    }
}
