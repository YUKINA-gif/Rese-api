<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function post(Request $request)
    {
        $items = User::where('email', $request->email)->first();
        if (Hash::check($request->password, $items->password)) 
        {
            return response()->json(['auth' => true], 200);
        } else {
            return response()->json(['auth' => false], 200);
       }
    }
}
