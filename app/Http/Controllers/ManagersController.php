<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ManagersController extends Controller
{
    /**
     * [GET]マネージャー情報の取得
     *
     *　入力値（ログインID）から
     *  マネージャー情報を取得する
     * 
     * @access public
     * @param Request $request  リクエストパラメータ
     * @return Response  マネージャー情報の取得、ない場合は404で返す
     */
    public function get(Request $request)
    {
        if ($request->has('login_id')) {
            $manager = Manager::where('login_id', $request->login_id)->first();
        }

        if ($manager) {
            return response()->json([
                'message' => 'Manager got successfully',
                'manager' => $manager
            ], 200);
        } else {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }
    }

    /**
     * [POST]管理者権限発行(ランダム数値)
     * 
     * IDとパスワードの生成(ランダムな文字列8)
     * 
     * @access public
     * @param Request $request リクエストパラメータ
     * @return Response login_id,passwordの生成,生成データを表示
     * @var timestamps $now  登録日時
     * @var string $login_id  ランダム生成したログインID
     * @var string $password  ランダム生成したパスワード
     * @var string $hashed_password  ハッシュしたパスワード
     * @var array $store_manager  新規レコード 
     */
    public function post(Request $request)
    {
        $now = Carbon::now();
        $password = Str::random(8);
        $hashed_password = Hash::make($password);

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
     * 入力値からmanagersデータベース情報と比較し
     * ログイン可能かどうか確かめる
     * 
     * @access public
     * @param Request $request リクエストパラメータ
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
