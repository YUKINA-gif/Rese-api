<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
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
        Booking::where("user_id", $request->user_id)->where("store_id", $request->store_id)->update($param);

        return response()->json([
            "message" => "Booking updated successfully"
        ], 200);
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
        $booking = Booking::where("user_id", $request->user_id)->where("id", $request->id)->delete();

        if ($booking) {
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
