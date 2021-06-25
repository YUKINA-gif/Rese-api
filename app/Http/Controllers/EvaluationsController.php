<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evaluation;
use Carbon\Carbon;

/**
 * [API]評価機能API class
 * 
 * 評価機能に関するコントローラー
 * 評価をする
 * 
 * @access public
 * @author Nakanishi Yukina
 * @category Evaluaton
 * @package Controller
 */
class EvaluationsController extends Controller
{
    /**
     * [POST]評価
     *
     *　店舗の評価をする
     * 
     * @access public
     * @param Request $request  リクエストパラメータ
     * @return Response  評価
     * @var array $evaluation  新規レコード
     * @var timestamps $now  現在日時
     */
    public function post(Request $request)
    {
        $now = Carbon::now();

        // バリデーション設定
        $request->validate([
            "user_id" => ["required"],
            "store_id" => ["required"],
            "evaluation" => ["required"],
        ]);

        $evaluation = new Evaluation;

        $result = $evaluation->fill([
            "user_id" => $request->user_id,
            "store_id" => $request->store_id,
            "evaluation" => $request->evaluation,
            "created_at" => $now,
            "updated_at" => $now,
        ])->save();

        if ($result) {
            return response()->json([
                "message" => "Evaluated successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Not found"
            ], 404);
        }

    }
}
