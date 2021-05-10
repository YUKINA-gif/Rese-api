<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersController extends Controller
{
    public function get(Request $request)
    {
        if ($request->has("email")) {
            User::where("email", $request->email)->get();
            return response()->json([
                "message" => "User got successfully"
            ], 200);
        } else {
            return response()->json([
                "status" => "not found"
            ], 404);
        }
    }
    public function post(Request $request)
    {
        $now = Carbon::now();
        $hashed_password = Hash::make($request->password);

        $param = new User;
        $param->fill([
            "name" => $request->name,
            "email" => $request->email,
            "password" => $hashed_password,
            "created_at" => $now,
            "updated_at" => $now,
        ]);
        $param->save();

        return response()->json([
            'message' => 'User created successfully'
        ], 200);
    }
    public function favorites(Request $request)
    {
        $data = User::find($request->user_id)->favorites;

        return response()->json([
            "data" => $data
        ], 200);
    }
    public function bookings(Request $request)
    {
        $data = User::find($request->user_id)->bookings;

        return response()->json([
            "data" => $data
        ], 200);
    }
}
