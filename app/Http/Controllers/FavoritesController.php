<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use Carbon\Carbon;

class FavoritesController extends Controller
{
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
        ],200);
    }
    public function delete(Request $request)
    {
        Favorite::where("store_id",$request->store_id)->where('user_id', $request->user_id)->delete();
        
        return response()->json([
            "message" => "Favorite deleted successfully"
        ],200);
    }
}
