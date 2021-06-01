<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * [API]ユーザー情報API class
 * 
 * ユーザー情報に関するコントローラー
 * ユーザー情報の取得、新規会員登録
 * 
 * @access public
 * @author Nakanishi Yukina
 * @category User
 * @package Controller
 */

class UsersController extends Controller
{
    /**
     * [GET]ユーザー情報の取得
     *
     *　入力値（メールアドレス）から
     *  ユーザー情報を取得する
     * 
     * @access public
     * @param Request $request  リクエストパラメータ
     * @return Response  ユーザー情報の取得、ない場合は404で返す
     */
    public function get(Request $request)
    {
        if ($request->has('email')) {
            $user = User::where('email', $request->email)->first();
        }

        if ($user) {
            return response()->json([
                'message' => 'User got successfully',
                'user' => $user
            ], 200);
        } else {
            return response()->json([
                'status' => 'not found'
            ], 404);
        }
    }

    /**
     * [POST]新規会員登録
     *
     *　リクエストで送られてきた情報を
     *  usersデータベースに登録する
     * 
     * @access public
     * @param Request $request  リクエストパラメータ
     * @return Response  会員登録
     * @var timestamps $now  登録日時
     * @var string $hashed_password  ハッシュしたパスワード
     * @var array $param  新規レコード
     */
    public function post(Request $request)
    {
        $now = Carbon::now();
        $hashed_password = Hash::make($request->password);

        // バリデーション設定
        $request->validate([
            "name" => ["required", "string"],
            "email" => ["required", "email",],
            "password" => ["required", "string", "min:8"],
        ]);

        $param = new User;
        $param->fill([
            "name" => $request->name,
            "email" => $request->email,
            "password" => $hashed_password,
            "created_at" => $now,
            "updated_at" => $now,
        ]);
        $param->save();

        return response()->json([
            'message' => 'User created successfully'
        ], 200);
    }
}
