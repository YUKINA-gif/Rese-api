<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;

/**
 * [API]予約機能API class
 * 
 * 予約機能に関するコントローラー
 * 予約の登録、更新、取消
 * 
 * @access public
 * @author Nakanishi Yukina
 * @category Booking
 * @package Controller
 */
class BookingController extends Controller
{
    /**
     * [GET]予約一覧取得
     *
     *　ユーザーID(リクエスト)から
     *  予約一覧を取得する
     * 
     * @access public
     * @param Request $request  リクエストパラメーター
     * @return Response  予約一覧表示
     * @var array $data ユーザーID(リクエスト)から予約一覧を探す
     */
    public function get(Request $request)
    {
        $data = Booking::where("user_id", $request->user_id)->with("store")->get();

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
     * [POST]予約登録
     * 
     * 予約の登録をする
     * 
     * @access public
     * @param Request $request リクエストパラメータ
     * @return Response 予約登録
     * @var timestamps $now  登録日時
     * @var array $booking  新規レコード
     */
    public function post(Request $request)
    {
        $now = Carbon::now();
        
        // バリデーション設定
        $request->validate([
            "user_id" => ["required"],
            "store_id" =>
            ["required"],
            "booking_date" =>
            ["required", "date", "after:tomorrow"],
            "booking_time" =>
            ["required",],
            "booking_number" =>
            ["required", "numeric"],
        ]);

        $booking = new Booking;
        $booking->fill([
            "user_id" => $request->user_id,
            "store_id" => $request->store_id,
            "booking_date" => $request->booking_date,
            "booking_time" => $request->booking_time,
            "booking_number" => $request->booking_number,
            "created_at" => $now,
            "updated_at" => $now,
        ])->save();

        return response()->json([
            "message" => "Booking successfully"
        ], 200);
    }

    /**
     * [PUT]予約更新
     * 
     * 予約内容の更新をする
     * 
     * @access public
     * @param Request $request リクエストパラメータ
     * @return Response 予約更新
     * @var array $param  更新希望日時、人数 
     */
    public function put(Request $request)
    {
        $param = [
            "booking_date" => $request->booking_date,
            "booking_time" => $request->booking_time,
            "booking_number" => $request->booking_number,
        ];
        $booking = Booking::where("id", $request->id)->where("user_id", $request->user_id)->where("store_id", $request->store_id)->update($param);

        if ($booking) {
            return response()->json([
                "message" => "Booking updated successfully",
                "data" => $request
            ], 200);
        } else {
            return response()->json([
                "message" => "Not found"
            ], 404);
        }
    }

    /**
     * [DELETE]予約取消
     * 
     * 予約の取消をする
     * 
     * @access public
     * @param Request $request リクエストパラメータ
     * @return Response 予約取消
     */
    public function delete(Request $request)
    {
        $booking_del = Booking::where("id", $request->id)->where("user_id", $request->user_id)->delete();

        if ($booking_del) {
            return response()->json([
                "message" => "Booking deleted successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Not found"
            ], 404);
        }
    }
}
