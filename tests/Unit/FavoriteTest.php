<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Database\Seeders\FavoriteSeeder;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

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

        // その他ランダムでfavoritesテーブルにデータを追加
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
     * [POST]お気に入り店舗登録
     *
     * 非正常系
     * リクエストパラメータがない場合
     * 
     * @return void
     * @test
     */
    public function 非正常系_ステータスコード500_favorite_post()
    {
        $response = $this->post("api/favorite");
        $response->assertStatus(500);
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
        $favorite = [
            "user_id" => "1",
            "store_id" =>"1",
        ];
        $response = $this->post("api/favorite",$favorite);
        $response->assertStatus(200)->assertJsonFragment([
            "message" => "Favorite created successfully"
        ]);
    }

    /**
     * [PUT]お気に入り店舗再登録
     *
     * 非正常系
     * リクエストパラメータがない場合
     * 
     * @return void
     * @test
     */
    public function 非正常系_ステータスコード404_favorite_put()
    {
        $response = $this->put("api/favorite");
        $response->assertStatus(404);
    }

    /**
     * [PUT]お気に入り店舗再登録
     *
     * 非正常系
     * リクエストパラメータあり
     * データベースにデータがない場合
     * 
     * @return void
     * @test
     */
    public function 非正常系_ステータスコード404_favorite_put_noDatabase()
    {
        $favorite = [
            "user_id" => "5",
            "store_id" => "1"
        ];
        $response = $this->put("api/favorite",$favorite);
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
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
        // お気に入り店舗データを作ってソフトデリート
        $favorite = [
            "user_id" => "5",
            "store_id" => "1"
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
     * 非正常系
     * リクエストパラメータがない場合
     * 
     * @return void
     * @test
     */
    public function 非正常系_ステータスコード404_favorite_delete()
    {
        $response = $this->delete("api/favorite");
        $response->assertStatus(404);
    }

    /**
     * [DELETE]お気に入り店舗削除
     *
     * 非正常系
     * リクエストパラメータあり
     * データベースにデータがない場合
     * 
     * @return void
     * @test
     */
    public function 非正常系_ステータスコード404_favorite_delete_noDatabase()
    {
        $favorite = [
            "user_id" => "5",
            "store_id" => "1"
        ];
        $response = $this->put("api/favorite", $favorite);
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
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
        // お気に入り店舗データを作成
        $favorite = [
            "user_id" => "5",
            "store_id" => "1"
        ];
        DB::table("favorites")->insert($favorite);

        // 削除する
        $response = $this->delete("api/favorite", $favorite);
        $response->assertStatus(200)->assertJsonFragment([
            "message" => "Favorite deleted successfully"
        ]);
    }
}