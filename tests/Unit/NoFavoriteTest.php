<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Database\Seeders\FavoriteSeeder;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\Favorite;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class NoFavoriteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 初期データ準備
     *
     * ユーザーデータ作成
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
    }

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
        // お気に入り店舗がない場合404を返す
        $response = $this->get("api/user/1/favorite");
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
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
        // リクエストパラメータ
        $favorite = [
            "user_id" => "1",
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
            "user_id" => "1",
            "store_id" => "100"
        ];
        $response = $this->put("api/favorite", $favorite);
        $response->assertStatus(404)->assertJsonFragment([
            "message" => "Not found"
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
