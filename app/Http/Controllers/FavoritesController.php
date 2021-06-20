<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use Carbon\Carbon;

/**
 * [API]お気に入り機能API class
 * 
 * お気に入り機能に関するコントローラー
 * お気に入りの登録、更新、削除
 * 
 * @access public
 * @author Nakanishi Yukina
 * @category Favorite
 * @package Controller
 */
class FavoritesController extends Controller
{
    /**
     * [GET]お気に入り店舗一覧取得
     *
     *　ユーザーID(リクエスト)から
     *  お気に入り一覧を取得する
     * 
     * @access public
     * @param Request $request  リクエストパラメータ
     * @return Response  お気に入り店舗一覧表示
     * @var array $data ユーザーID(リクエスト)からお気に入り店舗を探す エリアとジャンルも取得
     */
    public function get(Request $request)
    {
        $data = Favorite::where("user_id", $request->user_id)->with("store.area", "store.genre")->get();

        if (!empty($data->toArray())) {
            return response()->json([
                "data" => $data
            ], 200);
        } else {
            return response()->json([
                "message" => "Not found"
            ], 404);
        }
    }

    /**
     * [POST]お気に入り登録
     * [PUT]復元
     * [DELETE]削除
     * 
     * お気に入り店舗を登録する
     * すでに登録されていれば削除、
     * すでに削除されていれば復元する
     * 
     * @access public
     * @param Request $request リクエストパラメータ
     * @return Response お気に入り登録,復元,削除
     * @var timestamps $now  現在日時
     * @var array $favorite  新規レコード
     * @var array $seach_myfavorite すでに登録されているか調べる
     */
    public function favorites(Request $request)
    {
        // データがあるか調べる
        $seach_myfavorite =
            Favorite::where("store_id", $request->store_id)->where('user_id', $request->user_id)->withTrashed()->first();
        // なければ登録
        if (!$seach_myfavorite) {
            $now = Carbon::now();

            $favorite = new Favorite;
            $favorite->fill([
                "store_id" => $request->store_id,
                "user_id" => $request->user_id,
                "created_at" => $now,
                "updated_at" => $now
            ])->save();

            return response()->json([
                "message" => "Favorite created successfully"
            ], 200);
            // データがあり、削除されていれば復元する 
        } elseif ($seach_myfavorite->trashed()) {
            $favorite = $seach_myfavorite->restore();
            if ($favorite) {
                return response()->json([
                    "message" => "Favorite restored successfully"
                ], 200);
            } else {
                return response()->json([
                    "message" => "Not found"
                ], 404);
            }
            // そうでなければ削除する
        } else {
            $favorite = $seach_myfavorite->delete();
            if ($favorite) {
                return response()->json([
                    "message" => "Favorite deleted successfully"
                ], 200);
            } else {
                return response()->json([
                    "message" => "Not found"
                ], 404);
            }
        }
    }
}
