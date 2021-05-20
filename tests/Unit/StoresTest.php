<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\StoreSeeder;

class StoresTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 店舗全データ取得
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
     * 店舗全データ取得
     *
     * 正常系
     * データ情報がある場合
     * 
     * @return void
     * @test
     */
    public function 正常系_ステータスコード200_stores_get()
    {
        // storesテーブルに店舗データを作成
        $this->artisan("db:seed", ["--class" => StoreSeeder::class]);

        // 店舗データがある場合は200で返す
        $response = $this->get("api/stores");
        $response->assertStatus(200)->assertJsonFragment([
            "area_id" => "1",
            "genre_id" => "1",
            "name" => "仙人",
        ]);
    }

    /**
     * 指定店舗データ取得
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
        $response = $this->get("api/stores/0");
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
        ]);
    }

    /**
     * 指定店舗データ取得
     *
     * 正常系
     * データ情報がある場合
     * 
     * @return void
     * @test
     */
    public function 正常系_ステータスコード200_store_get()
    {
        // storesテーブルに店舗データを作成
        $this->artisan("db:seed", ["--class" => StoreSeeder::class]);

        // 指定店舗データがある場合は200で返す
        $response = $this->get("api/stores/22");
        $response->assertStatus(200)->assertJsonFragment([
            "area_id" => "1",
            "genre_id" => "1",
            "name" => "仙人"
        ]);
    }
}
