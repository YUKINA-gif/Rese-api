<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingMail;
use App\Mail\BookingCancelMail;
use App\Models\User;

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
     * @param Request $request  リクエストパラメータ
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
     * @var timestamps $now  現在日時
     */
    public function post(Request $request)
    {
        $now = Carbon::now();

        // バリデーション設定
        $request->validate([
            "user_id" => ["required"],
            "store_id" => ["required"],
            "booking_date" => ["required", "date", "after:tomorrow"],
            "booking_time" => ["required",],
            "booking_number" => ["required", "numeric"],
        ]);

        $booking = new Booking;
        
        $result = $booking->fill([
            "user_id" => $request->user_id,
            "store_id" => $request->store_id,
            "booking_date" => $request->booking_date,
            "booking_time" => $request->booking_time,
            "booking_number" => $request->booking_number,
            "created_at" => $now,
            "updated_at" => $now,
        ])->save();

        if($result){
            $user = User::where("id",$request->user_id)->first();
            Mail::to($user->email)->send(new BookingMail($user));
        }
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
     * @var array $booking  既存レコードを更新する
     */
    public function put(Request $request)
    {
        // バリデーション設定
        $request->validate([
            "user_id" => ["required"],
            "store_id" => ["required"],
            "booking_date" => ["required", "date", "after:tomorrow"],
            "booking_time" => ["required",],
            "booking_number" => ["required", "numeric"],
        ]);

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
     * @var array $booking_del 既存レコードを削除する
     */
    public function delete(Request $request)
    {
        $booking_del = Booking::where("id", $request->id)->where("user_id", $request->user_id)->delete();

        if ($booking_del) {
            $user = User::where("id", $request->user_id)->first();
            Mail::to($user->email)->send(new BookingCancelMail($user));
        }

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

    /**
     * [GET]予約取得(管理用)
     *
     *  全ての予約一覧を取得する
     * 
     * @access public
     * @param Request $request  リクエストパラメータ
     * @return Response  予約、店舗、ユーザーデータを取得
     * @var array $data 予約、店舗、ユーザーデータを取得
     */
    public function get_all_bookings(Request $request)
    {
        $data = Booking::with("store", "user")->withTrashed()->get();

        if (!empty($data->toArray())) {
            return response()->json([
                "booking" => $data
            ], 200);
        } else {
            return response()->json([
                "message" => "Not found"
            ], 404);
        }
    }
}
