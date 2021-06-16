<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Area;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $stores = Store::with("area", "genre")->with("favorites", function ($q) use ($request) {
            $q->where("user_id", $request->user_id);
        })->when($request->name, function ($q) use ($request) {
            $q->where("name", "like", "%$request->name%");
        })->when($request->area_id, function ($q) use ($request) {
            $q->where("area_id", $request->area_id);
        })->when($request->genre_id, function ($q) use ($request) {
            $q->where("genre_id", $request->genre_id);
        })->get();

        if (!empty($stores->toArray())) {
            return response()->json([
                "store" => $stores
            ], 200);
        } else {
            return response()->json([
                "message" => "Not found"
            ], 404);
        }
    }

    /**
     * [POST]店舗登録
     * 
     * 店舗を登録する
     * 
     * @access public
     * @return Response 店舗登録
     * @var array $store  店舗詳細データ
     */
    public function post(Request $request)
    {
        $request->validate([
            "name" => ["required", "string"],
            "overview" => ["required", "string"],
            "image" => ["required", "image"],
            "area_id" => ["required", "numeric"],
            "genre_id" => ["required", "numeric"],
        ]);

        $image = $request->image;
        $path = Storage::disk('s3')->putFile('/', $image, 'public');

        $store = new Store;
        $result = $store->fill([
            "name" => $request->name,
            "overview" => $request->overview,
            "image" => $path,
            "area_id" => $request->area_id,
            "genre_id" => $request->genre_id,
        ])->save();

        if ($result) {
            return response()->json([
                "message" => "Store created successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "An error has occurred"
            ], 404);
        }
    }

    /**
     * [PUT]店舗情報更新
     * 
     * 店舗情報を更新する
     * 
     * @access public
     * @return Response 店舗登録
     * @var array $store  店舗詳細データ
     */
    public function put(Request $request)
    {
        $request->validate([
            "id" => ["required", "numeric"],
            "name" => ["required", "string"],
            "overview" => ["required", "string"],
            "image" => ["required", "image"],
            "area_id" => ["required", "numeric"],
            "genre_id" => ["required", "numeric"],
        ]);

        $image_pass = Store::where("id", $request->id)->get("image");

        foreach ($image_pass as $image) {
            $result = Storage::disk('s3')->delete($image->image);
        };

        if ($result) {
            $image = $request->image;
            $path = Storage::disk('s3')->putFile('/', $image, 'public');

            $items = [
                "name" => $request->name,
                "overview" => $request->overview,
                "image" => $path,
                "area_id" => $request->area_id,
                "genre_id" => $request->genre_id,
            ];
        };
        
        $result_update = Store::where("id", $request->id)->update($items);

        if ($result_update) {
            return response()->json([
                "message" => "Store updated successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Not Found"
            ], 404);
        }
    }

    /**
     * [DELETE]店舗削除
     * 
     * 店舗を削除する
     * 
     * @access public
     * @return Response 店舗削除
     * @var array $store  店舗詳細データ
     */
    public function delete(Request $request)
    {

        $image_pass = Store::where("id", $request->id)->get("image");

        foreach ($image_pass as $image) {
            $result = Storage::disk('s3')->delete($image->image);
        };
        if ($result) {
            Store::where("id", $request->id)->delete();
            return response()->json([
                "message" => "Store deleted successfully",
                "data" => $result
            ], 200);
        } else {
            return response()->json([
                "message" => "Not Found",
                "data" => $image_pass,
            ], 404);
        }
    }
}
