<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Database\Seeders\FavoriteSeeder;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Database\Seeders\DatabaseSeeder;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 初期データ準備
     *
     * ユーザーデータ作成
     * お気に入り店舗登録
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

        // ランダムでユーザーデータ20人分追加
        $this->artisan("db:seed", ["--class" => DatabaseSeeder::class]);

        // お気に入り店舗登録
        $now = Carbon::now();
        $favorite = [
            "user_id" => "1",
            "store_id" => "1",
            "created_at" => $now,
            "updated_at" => $now,
        ];
        DB::table("favorites")->insert($favorite);

        $favorite = [
            "user_id" => "1",
            "store_id" => "5",
            "created_at" => $now,
            "updated_at" => $now,
        ];
        DB::table("favorites")->insert($favorite);

        // ランダムでお気に入り店舗10件追加
        $this->artisan("db:seed", ["--class" => FavoriteSeeder::class]);
    }

    /**
     * [GET]ユーザーお気に入り店舗取得
     *
     * 異常系
     * テーブルに情報がない場合
     * 
     * @return void
     * @test
     */
    public function 異常系_ステータスコード404_user_favorite()
    {
        // お気に入り店舗がない場合404を返す
        $response = $this->get("api/user/20/favorite");
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
        ]);
    }

    /**
     * [POST]お気に入り店舗登録
     *
     * 異常系
     * リクエストパラメータがない場合
     * 
     * @return void
     * @test
     */
    public function 異常系_ステータスコード500_favorite_post()
    {
        $response = $this->post("api/favorite");
        $response->assertStatus(500);
    }

    /**
     * [PUT]お気に入り店舗再登録
     *
     * 異常系
     * リクエストパラメータがない場合
     * 
     * @return void
     * @test
     */
    public function 異常系_ステータスコード404_favorite_put()
    {
        $response = $this->put("api/favorite");
        $response->assertStatus(404);
    }

    /**
     * [PUT]お気に入り店舗再登録
     *
     * 異常系
     * リクエストパラメータあり
     * データベースにデータがない場合
     * 
     * @return void
     * @test
     */
    public function 異常系_ステータスコード404_favorite_put_noDatabase()
    {
        // リクエストパラメータ
        $favorite = [
            "user_id" => "15",
            "store_id" => "10"
        ];
        // データベースにデータがない場合404を返す
        $response = $this->put("api/favorite", $favorite);
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
        ]);
    }

    /**
     * [DELETE]お気に入り店舗削除
     *
     * 異常系
     * リクエストパラメータがない場合
     * 
     * @return void
     * @test
     */
    public function 異常系_ステータスコード404_favorite_delete()
    {
        $response = $this->delete("api/favorite");
        $response->assertStatus(404);
    }

    /**
     * [DELETE]お気に入り店舗削除
     *
     * 異常系
     * リクエストパラメータあり
     * データベースにデータがない場合
     * 
     * @return void
     * @test
     */
    public function 異常系_ステータスコード404_favorite_delete_noDatabase()
    {
        $favorite = [
            "user_id" => "15",
            "store_id" => "100"
        ];
        $response = $this->put("api/favorite", $favorite);
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
        ]);
    }

    /**
     * [GET]ユーザーお気に入り店舗取得
     *
     * 正常系
     * テーブルに情報がある場合
     * 
     * @return void
     * @test
     */
    public function 正常系_ステータスコード200_user_favorites()
    {
        // お気に入り店舗がある場合200を返す
        $response = $this->get("api/user/1/favorite");
        $response->assertStatus(200)->assertJsonFragment([
            "user_id" => "1",
            "store_id" => "1",
        ], [
            "user_id" => "1",
            "store_id" => "5",
        ]);
    }

    /**
     * [POST]お気に入り店舗登録
     *
     * 正常系
     * リクエストパラメータがある場合
     * 
     * @return void
     * @test
     */
    public function 正常系_ステータスコード200_favorite_post()
    {
        // リクエストパラメータ
        $favorite = [
            "user_id" => "1",
            "store_id" => "3",
        ];
        // 登録が完了した場合200を返す
        $response = $this->post("api/favorite", $favorite);
        $response->assertStatus(200)->assertJsonFragment([
            "message" => "Favorite created successfully"
        ]);
    }

    /**
     * [PUT]お気に入り店舗再登録
     *
     * 正常系
     * 
     * @return void
     * @test
     */
    public function 正常系_ステータスコード200_favorite_put()
    {
        // お気に入り店舗データを作成、削除(ソフトデリート)
        $favorite = [
            "user_id" => "1",
            "store_id" => "10"
        ];
        DB::table("favorites")->insert($favorite);
        DB::table("favorites")->delete($favorite);

        // 再登録する
        $response = $this->put("api/favorite", $favorite);
        $response->assertStatus(200)->assertJsonFragment([
            "message" => "Favorite restored successfully"
        ]);
    }

    /**
     * [DELETE]お気に入り店舗削除
     *
     * 正常系
     * 
     * @return void
     * @test
     */
    public function 正常系_ステータスコード200_favorite_delete()
    {
        // リクエストパラメータ
        $favorite = [
            "user_id" => "1",
            "store_id" => "5"
        ];

        // 削除する
        $response = $this->delete("api/favorite", $favorite);
        $response->assertStatus(200)->assertJsonFragment([
            "message" => "Favorite deleted successfully"
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
