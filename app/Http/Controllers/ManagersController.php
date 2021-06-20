<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * [API]管理者API class
 * 
 * 管理者に関するコントローラー
 * 
 * @access public
 * @author Nakanishi Yukina
 * @category Manager
 * @package Controller
 */
class ManagersController extends Controller
{
    /**
     * [POST]管理者権限発行(ランダム数値)
     * 
     * IDとパスワードの生成(ランダムな英字8文字)
     * 
     * @access public
     * @param Request $request リクエストパラメータ
     * @return Response リクエストIDをもとにpasswordの生成,生成データを表示、登録
     * @var timestamps $now  登録日時
     * @var string $login_id  ログインID(リクエスト)
     * @var string $password  ランダム生成したパスワード
     * @var string $hashed_password  ハッシュしたパスワード
     * @var array $manager  新規レコード 
     */
    public function post(Request $request)
    {
        $now = Carbon::now();
        $password = Str::random(8);
        $hashed_password = Hash::make($password);

        // バリデーション設定
        $request->validate([
            "login_id" => ["required", "string",]
        ]);

        $manager = new Manager;
        $manager->fill([
            "login_id" => $request->login_id,
            "password" => $hashed_password,
            "created_at" => $now,
            "updated_at" => $now,
        ])->save();

        return response()->json([
            "message" => "Store manager created successfully",
            "login_id" => $manager->login_id,
            "password" => $password,
        ], 200);
    }

    /**
     * [POST]ログイン操作
     * 
     * リクエストからmanagersデータベース情報と比較し
     * ログイン可能かどうか確かめる
     * 
     * @access public
     * @param Request $request リクエストパラメータ
     * @return Response ログイン可もしくは不可
     * @var object $items  ログインID(リクエスト)からデータベース内の情報を探す
     */
    public function login(Request $request)
    {
        // バリデーション設定
        $request->validate([
            "login_id" => ["required", "string",],
            "password" => ["required", "string",],
        ]);

        $items = Manager::where('login_id', $request->login_id)->first();
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
