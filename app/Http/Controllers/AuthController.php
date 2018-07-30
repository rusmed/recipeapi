<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function signin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (Hash::check($request->input('password'), $user->password)) {

            $token = base64_encode(str_random(40));
            User::where('email', $request->input('email'))->update(['token' => "$token", 'token_expired' => 'NOW()']);

            return response()->json(['status' => 'success', 'token' => $token, 'message' => 'NOTE: The lifetime of the token is 10 minutes from the last request']);

        }

        return response()->json(['status' => 'Authentication failed'],401);

    }

    public function signup(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => app('hash')->make($request->input('password'))
        ];

        $user = User::create($data);

        return response()->json($user, 201);
    }
}
