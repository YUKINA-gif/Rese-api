<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Database\Seeders\StoreSeeder;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StoresTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * 初期データ準備
     * 
     * 店舗データ追加
     * 
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // ユーザーデータ作成
        $user = new User;
        $user->fill([
            "name" => "test",
            "email" => "test@test.com",
            "password" => Hash::make("testtest"),
        ])->save();

        // 店舗データを作成
        $this->artisan("db:seed", ["--class" => StoreSeeder::class]);
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
        // 初期準備した店舗データを削除
        Artisan::call('migrate:refresh');

        // 店舗データがない場合は404を返す
        $response = $this->get("api/stores/0");
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
        $response = $this->get("api/store/150");
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
        ]);
    }

    /**
     * [GET]店舗検索
     *
     * 異常系
     * データ情報がない場合
     * 
     * @return void
     * @test
     */
    public function 異常系_ステータスコード404_store_seach()
    {
        // 初期準備した店舗データを削除
        Artisan::call('migrate:refresh');

        // 指定店舗データがない場合は404で返す
        $response = $this->get("api/storesSearch/0");
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
        ]);
    }

    /**
     * [POST]店舗登録
     *
     * 異常系
     * リクエストパラメータがない場合
     * 
     * @return void
     * @test
     */
    public function 異常系_ステータスコード302_store_post()
    {
        $response = $this->post("/api/stores");
        $response->assertStatus(302);
    }

    /**
     * [PUT]店舗情報更新
     *
     * 異常系
     * リクエストパラメータがない場合
     * 
     * @return void
     * @test
     */
    public function 異常系_ステータスコード302_store_put()
    {
        $response = $this->put("/api/stores");
        $response->assertStatus(302);
    }

    /**
     * [PUT]店舗情報更新
     *
     * 異常系
     * DBにデータがない場合
     * 
     * @return void
     * @test
     */
    public function 異常系_ステータスコード404_store_put()
    {
        $store = [
            "id" => "200",
            "name" => "一楽",
            "overview" => "数日かけて煮込んだ豚骨スープが自慢のお店です。",
            "area_id" => "2",
            "genre_id" => "5"
        ];
        $response = $this->put("/api/stores", $store);
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
        // 店舗データがある場合は200で返す
        $response = $this->get("api/stores/1");
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
        // 指定店舗データがある場合は200で返す
        $response = $this->get("api/store/1");
        $response->assertStatus(200)->assertJsonFragment([
            "area_id" => "1",
            "genre_id" => "1",
            "name" => "仙人"
        ]);
    }

    /**
     * [GET]店舗検索
     *
     * 正常系
     * データ情報がない場合
     * 
     * @return void
     * @test
     */
    public function 正常系_ステータスコード200_store_seach()
    {
        // 指定店舗データがある場合は200で返す
        $response = $this->get("api/storesSearch/0");
        $response->assertStatus(200)->assertJsonFragment([
            "area_id" => "1",
            "genre_id" => "1",
            "name" => "仙人"
        ]);
    }

    /**
     * [PUT]店舗情報更新
     *
     * 正常系
     * 
     * @return void
     * @test
     */
    public function 正常系_ステータスコード200_store_put()
    {
        $store = [
            "id" => "20",
            "name" => "一楽",
            "overview" => "数日かけて煮込んだ豚骨スープが自慢のお店です。",
            "area_id" => "2",
            "genre_id" => "5"
        ];
        $response = $this->put("/api/stores", $store);
        $response->assertStatus(200)->assertJsonFragment([
            "message" => "Store updated successfully"
        ]);
    }

    /**
     * データリフレッシュ
     * 
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }
}
