<?php

namespace App\Http\Controllers;

use App\Models\Store;
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
    public function index()
    {
        $stores = Store::all();
        return response()->json([
            "store" => $stores
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function show(Store $store)
    {
        $store = Store::where("id", $store->id)->first();
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        //
    }
}
