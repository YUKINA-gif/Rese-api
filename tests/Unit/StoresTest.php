<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\StoreSeeder;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StoresTest extends TestCase
{
    use RefreshDatabase;

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
     * @group testing
     */
    public function 異常系_ステータスコード404_stores_get()
    {
        // 初期準備した店舗データを削除
        Artisan::call('migrate:refresh');

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
     * @group testing
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
     * [GET]店舗全データ取得
     *
     * 正常系
     * データ情報がある場合
     * 
     * @return void
     * @test
     * @group testing
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
     * @group testing
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
