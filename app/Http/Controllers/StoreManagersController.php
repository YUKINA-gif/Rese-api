<?php

namespace App\Http\Controllers;

use App\Models\StoreManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class StoreManagersController extends Controller
{
    /**
     * [GET]ストアマネージャー情報の取得
     *
     *　入力値（ログインID）から
     *  ストアマネージャー情報を取得する
     * 
     * @access public
     * @param Request $request  リクエストパラメータ
     * @return Response  ストアマネージャー情報の取得、ない場合は404で返す
     */
    public function get(Request $request)
    {
        if ($request->has('login_id')) {
            $StoreManager = StoreManager::where('login_id', $request->login_id)->first();
        }

        if ($StoreManager) {
            return response()->json([
                'message' => 'StoreManager got successfully',
                'storeManager' => $StoreManager
            ], 200);
        } else {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }
    }

    /**
     * [POST]店舗代表者権限発行
     * 
     * 入力値からIDとパスワードの登録
     * 
     * @access public
     * @param Request $request リクエストパラメータ
     * @return Response 登録完了
     * @var timestamps $now  登録日時
     * @var string $hashed_password  ハッシュしたパスワード
     * @var array $store_manager  新規レコード
     * @var object 
     */
    public function post(Request $request)
    {
        $now = Carbon::now();
        $password = Str::random(8);
        $hashed_password = Hash::make($password);

        // バリデーション設定
        $request->validate([
            "login_id" => ["required", "string"],
        ]);

        $store_manager = new StoreManager;
        $store_manager->fill([
            "login_id" => $request->login_id,
            "password" => $hashed_password,
            "created_at" => $now,
            "updated_at" => $now,
        ])->save();

        return response()->json([
            "message" => "Store manager created successfully",
            "login_id" => $store_manager->login_id,
            "password" => $password,
        ], 200);
    }

    /**
     * [POST]ログイン操作
     * 
     * 入力値からmanagersデータベース情報と比較し
     * ログイン可能かどうか確かめる
     * 
     * @access public
     * @param Request $request リクエストパラメーター
     * @return Response ログイン可もしくは不可
     * @var object $items  ログインID(入力値)からデータベース内の情報を探す
     */
    public function login(Request $request)
    {
        // バリデーション設定
        $request->validate([
            "login_id" => ["required", "string",],
            "password" => ["required", "string",],
        ]);

        $items = StoreManager::where('login_id', $request->login_id)->first();
        if (Hash::check($request->password, $items->password)) {
            return response()->json([
                'auth' => true,
            ], 200);
        } else {
            return response()->json([
                'auth' => false
            ], 404);
        }
    }
}
