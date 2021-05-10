<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;

class BookingController extends Controller
{
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
        ]);

        $booking->save();

        return response()->json([
            "message" => "Booking successfully"
        ], 200);
    }
    public function delete(Request $request)
    {
        Booking::where("user_id", $request->user_id)->where("booking_id", $request->booking_id)->delete();

        return response()->json([
            "message" => "Booking deleted successfully"
        ], 200);
    }
}
