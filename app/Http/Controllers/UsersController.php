<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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
     * [getメソッド]ユーザー情報の取得
     *
     *　入力値（メールアドレス、パスワ ード）から
     *  ユーザー情報を取得する
     * 
     * @access public
     * @param Request $request    $request メールアドレス、パスワード（入力値）
     * @return Response ユーザー情報の取得、ない場合は404で返す
     */
    public function get(Request $request)
    {
        if ($request->has("email")) {
            User::where("email", $request->email)->get();
            return response()->json([
                "message" => "User got successfully"
            ], 200);
        } else {
            return response()->json([
                "status" => "not found"
            ], 404);
        }
    }
    /**
     * [postメソッド]新規会員登録
     *
     *　リクエストで送られてきた情報を
     *  usersデータベースに登録する
     * 
     * @access public
     * @param Request $request  名前、メールアドレス、パスワード（入力値）
     * @return Response  会員登録
     */
    public function post(Request $request)
    {
        /**
         * @var timestamps $now  登録日時
         * @var string $hashed_password  ハッシュしたパスワード
         * @var array $param  新規レコード
         */
        $now = Carbon::now();
        $hashed_password = Hash::make($request->password);

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
    public function favorites(Request $request)
    {
        $data = User::find($request->user_id)->favorites;

        return response()->json([
            "data" => $data
        ], 200);
    }
    public function bookings(Request $request)
    {
        $data = User::find($request->user_id)->bookings;

        return response()->json([
            "data" => $data
        ], 200);
    }
}
