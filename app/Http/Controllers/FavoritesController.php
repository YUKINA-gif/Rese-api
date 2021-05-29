<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\User;
use App\Models\Store;
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
     * @var array $data ユーザーID(リクエスト)からお気に入り店舗を探す
     */
    public function get(Request $request)
    {
        $data = Favorite::where("user_id",$request->user_id)->with("store.area","store.genre")->get();
      

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
     * 
     * お気に入り店舗を登録する
     * 
     * @access public
     * @param Request $request リクエストパラメーター
     * @return Response お気に入り登録
     * @var timestamps $now  登録日時
     * @var array $favorite  新規レコード
     */
    public function post(Request $request)
    {
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
    }

    /**
     * [PUT]お気に入り削除を復元
     * 
     * お気に入り削除していたデータを復元する
     * 
     * @access public
     * @param Request $request リクエストパラメーター
     * @return Response お気に入り削除分を復元
     */
    public function restore(Request $request)
    {
        $favorite = Favorite::where("store_id", $request->store_id)->where('user_id', $request->user_id)->restore();

        if ($favorite) {
            return response()->json([
                "message" => "Favorite restored successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Not found"
            ], 404);
        }
    }

    /**
     * [DELETE]お気に入り削除
     * 
     * お気に入り登録している店舗を削除する
     * ソフトデリートのためデータベースにデータは残る
     * 
     * @access public
     * @param Request $request リクエストパラメーター
     * @return Response お気に入り削除
     */
    public function delete(Request $request)
    {
        $favorite = Favorite::where("store_id", $request->store_id)->where('user_id', $request->user_id)->delete();

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
