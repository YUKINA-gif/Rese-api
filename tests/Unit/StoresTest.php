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
     * @return void
     * @test
     */
    public function stores_get()
    {
        // 店舗データがない場合は404を返す
        $response = $this->get("api/stores");
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
        ]);

        // storesテーブルに店舗データを作成
        $this->seed(StoreSeeder::class);

        // 店舗データがある場合は200で返す
        $response = $this->get("api/stores");
        $response->assertStatus(200)->assertJsonFragment([
            "id" => 1,
            "area_id" => "1",
            "genre_id" => "1",
        ]);
    }

    /**
     * 店舗データ取得
     *
     * @return void
     * @test
     */
    public function store_get()
    {
        // 店舗データがない場合は404を返す
        $response = $this->get("api/stores/25");
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
        ]);

        // storesテーブルに店舗データを作成
        $this->seed(StoreSeeder::class);

        // 店舗データがある場合は200で返す
        $response = $this->get("api/stores/1");
        $response->assertStatus(200)->assertJsonFragment([
            "area_id" => "1",
            "genre_id" => "1",
            "name" => "仙人"
        ]);
    }

}
