<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * [API]ログインAPI class
 * 
 * ログインに関するコントローラー
 * 
 * @access public
 * @author Nakanishi Yukina
 * @category Login
 * @package Controller
 */
class LoginController extends Controller
{
    /**
     * [POST]ログイン操作
     * 
     * 入力値からusersデータベース情報と比較し
     * ログイン可能かどうか確かめる
     * 
     * @access public
     * @param Request $request リクエストパラメータ
     * @return Response ログイン可もしくは不可
     * @var object $items  メールアドレス(リクエスト)からデータベース内のユーザー情報を探す
     */
    public function post(Request $request)
    {
        // バリデーション設定
        $request->validate([
            "email" => ["required", "email",],
            "password" => ["required", "string",],
        ]);

        $items = User::where('email', $request->email)->first();
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
