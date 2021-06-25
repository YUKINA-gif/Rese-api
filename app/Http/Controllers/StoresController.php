<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Area;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * [API]店舗情報取得 class
 * 
 * 店舗情報取得に関するコントローラー
 * 店舗一覧、詳細データ取得
 * 店舗登録、更新、削除
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
     * @param Request $request  リクエストパラメータ
     * @return Response 店舗一覧表示
     * @var array $stores  店舗全データ
     * @var array $area  エリア一覧
     * @var array $geenre  ジャンル一覧
     * @var array $item  $storesと$areaと$genreを配列に入れる
     */
    public function get(Request $request)
    {
        $stores = Store::with("area", "genre", "evals:store_id,evaluation")->with("favorites", function ($q) use ($request) {
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
     * @param Request $request  リクエストパラメータ
     * @return Response 店舗詳細データ表示　エリア、ジャンルも取得
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
     * @param Request $request  リクエストパラメータ
     * @return Response 店舗検索データ表示
     * @var array $stores  店舗検索データ
     */
    public function searchStore(Request $request)
    {
        $stores = Store::with("area", "genre")->with("favorites", function ($q) use ($request) {
            // ユーザーIDからお気に入り情報を探す
            $q->where("user_id", $request->user_id);
            // 店名(入力値)から該当店舗を探す
        })->when($request->name, function ($q) use ($request) {
            $q->where("name", "like", "%$request->name%");
            // エリア(入力値)から該当エリア店舗を探す　
        })->when($request->area_id, function ($q) use ($request) {
            $q->where("area_id", $request->area_id);
            // ジャンル(入力値)から該当エリア店舗を探す
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
     * @param Request $request  リクエストパラメータ
     * @return Response 店舗登録
     * @var array $store  リクエスト店舗詳細データ
     * @var image $image 画像(リクエスト)
     * @var string $path $imageをS3に保存しパスを取得
     * @var array $result リクエスト店舗詳細データをDBに保存
     */
    public function post(Request $request)
    {
        // バリデーション設定
        $request->validate([
            "name" => ["required", "string"],
            "overview" => ["required", "string"],
            "image" => ["required", "image"],
            "area_id" => ["required", "numeric"],
            "genre_id" => ["required", "numeric"],
        ]);

        $image = $request->image;
        // S3に画像を保存
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
     * [POST]店舗画像更新
     * 
     * 店舗画像を更新する
     * 
     * @access public
     * @param Request $request  リクエストパラメータ
     * @return Response 店舗画像更新
     * @var string $image_pass  画像のパス
     * @var array $image  パスを配列に入れ回す
     * @var string $req_image  画像(リクエスト)
     * @var array $image_box  画像(リクエスト)のパスを入れる用の配列
     * @var string $image_update 画像(リクエスト)をDBに更新
     */
    public function store_image_update(Request $request)
    {
        // バリデーション設定
        $request->validate([
            "image" => ["required", "image"],
        ]);
        // DBから画像を取得
        $image_pass = Store::where("id", $request->id)->get("image");
        // 取得した画像をS3から削除する
        foreach ($image_pass as $image) {
            Storage::disk('s3')->delete($image->image);
        };
        // 画像(リクエスト)
        $req_image = $request->image;
        // 画像(リクエスト)をS3に保存
        $path = Storage::disk('s3')->putFile('/', $req_image, 'public');

        // S3へ入れた際のパスを取得し、
        $image_box = [
            "image" => $path,
        ];
        // 画像(リクエスト)をDBに更新
        $image_update = Store::where("id", $request->id)->update($image_box);

        if ($image_update) {
            return response()->json([
                "message" => "Store image updated successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Not found"
            ], 404);
        }
    }

    /**
     * [PUT]店舗情報更新
     * 
     * 店舗情報を更新する
     * 
     * @access public
     * @param Request $request  リクエストパラメータ
     * @return Response 店舗登録
     * @var array $items 店舗データ(リクエスト)
     * @var array $result_update 店舗データ(リクエスト)をDBへ更新
     */
    public function put(Request $request)
    {
        // バリデーション設定
        $request->validate([
            "id" => ["required", "numeric"],
            "name" => ["required", "string"],
            "overview" => ["required", "string"],
            "area_id" => ["required", "numeric"],
            "genre_id" => ["required", "numeric"],
        ]);

        $items = [
            "name" => $request->name,
            "overview" => $request->overview,
            "area_id" => $request->area_id,
            "genre_id" => $request->genre_id,
        ];

        $result_update = Store::where("id", $request->id)->update($items);

        if ($result_update) {
            return response()->json([
                "message" => "Store updated successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Not found"
            ], 404);
        }
    }

    /**
     * [DELETE]店舗削除
     * 
     * 店舗を削除する
     * 
     * @access public
     * @param Request $request  リクエストパラメータ
     * @return Response 店舗削除
     * @var string $image_pass DBから画像のパスを取得
     * @var array $image  パスを配列に入れ回す
     * @var string $result ID(リクエスト)から店舗を削除
     */
    public function delete(Request $request)
    {

        $image_pass = Store::where("id", $request->id)->get("image");

        foreach ($image_pass as $image) {
            Storage::disk('s3')->delete($image->image);
        };
        $result = Store::where("id", $request->id)->delete();
        if ($result) {
            return response()->json([
                "message" => "Store deleted successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Not found"
            ], 404);
        }
    }
}
