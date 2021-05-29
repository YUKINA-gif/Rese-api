<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Favorite;
use Illuminate\Http\Request;

/**
 * [API]店舗情報取得 class
 * 
 * 店舗情報取得に関するコントローラー
 * 店舗一覧取得、店舗詳細データ取得
 * 
 * @access public
 * @author Nakanishi Yukina
 * @category Store
 * @package Controller
 */
class StoresController extends Controller
{
    /**
     * [GET]店舗一覧取得
     * 
     * 店舗全データを取得する
     * 
     * @access public
     * @return Response 店舗一覧表示
     * @var array $stores  店舗全データ
     */
    public function get(Request $request)
    {
        $stores = Store::with("area", "genre")->get();
        $area = Area::get();
        $genre = Genre::get();
        $favorite = Favorite::where("user_id",$request->user_id)->get("store_id");

        $item = [
            "store" => $stores,
            "favorite" => $favorite,
            "area" => $area,
            "genre" => $genre,
        ];

        if (!empty($stores->toArray())) {
            return response()->json([
                "item" => $item
            ], 200);
        } else {
            return response()->json([
                "message" => "Not found"
            ], 404);
        }
    }

    /**
     * [GET]店舗詳細データ取得
     * 
     * 店舗詳細データを取得する
     * 
     * @access public
     * @return Response 店舗詳細データ表示
     * @var array $store  店舗詳細データ
     */
    public function getStore(Request $request)
    {
        $store = Store::where("id", $request->id)->with("area", "genre")->first();
        if ($store) {
            return response()->json([
                'message' => 'OK',
                'store' => $store
            ], 200);
        } else {
            return response()->json([
                'message' => 'Not found',
            ], 404);
        }
    }
}
