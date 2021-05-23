<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\StoreSeeder;
use Illuminate\Support\Facades\Artisan;

class StoresTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 初期データなし
     * 
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * [GET]店舗全データ取得
     *
     * 異常系
     * データ情報がない場合
     * 
     * @return void
     * @test
     */
    public function 異常系_ステータスコード404_stores_get()
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
     * 異常系
     * データ情報がない場合
     * 
     * @return void
     * @test
     */
    public function 異常系_ステータスコード404_store_get()
    {
        // 店舗データがない場合は404を返す
        $response = $this->get("api/stores/150");
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
        ]);
    }

    /**
     * [GET]店舗全データ取得
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
     * [GET]指定店舗データ取得
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
        $response = $this->get("api/stores/1");
        $response->assertStatus(200)->assertJsonFragment([
            "area_id" => "1",
            "genre_id" => "1",
            "name" => "仙人"
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
