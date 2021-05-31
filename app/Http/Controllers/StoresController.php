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
     * @var array $area  エリア一覧
     * @var array $geenre  ジャンル一覧
     * @var array $item  $storesと$areaと$genreを配列に入れる
     */
    public function get(Request $request)
    {
        $stores = Store::with("area", "genre")->with("favorites", function ($q) use ($request) {
            $q->where("user_id", $request->user_id);
        })->get();
        $area = Area::get();
        $genre = Genre::get();

        $item = [
            "store" => $stores,
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

    /**
     * [GET]店舗検索データ取得
     * 
     * 店舗検索データを取得する
     * 
     * @access public
     * @return Response 店舗詳細データ表示
     * @var array $store  店舗詳細データ
     */
    public function seachStore(Request $request)
    {
        $store = Store::when($request->name, function ($q) use ($request) {
            $q->where("name", "like", "%$request->name%");
        })->when($request->area_id, function ($q) use ($request) {
            $q->where("area_id", $request->area_id);
        })->when($request->genre_id, function ($q) use ($request) {
            $q->where("genre_id", $request->genre_id);
        })->get();

        if ($store) {
            return response()->json([
                "store" => $store
            ], 200);
        } else {
            return response()->json([
                "message" => "Not found"
            ], 404);
        }
    }
}
