<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * [API]ログアウトAPI class
 * 
 * ログアウトに関するコントローラー
 * 
 * @access public
 * @author Nakanishi Yukina
 * @category Logout
 * @package Controller
 */
class LogoutController extends Controller
{
    /**
     * [POST]ログアウト機能
     * 
     * ログアウトをする
     * 
     * @access public
     * @return Response ログアウトを返す
     */
    public function post(Request $request)
    {
        return response()->json(['auth' => false], 200);
    }
}
