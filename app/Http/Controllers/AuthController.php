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
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (is_object($user) && Hash::check($request->input('password'), $user->password)) {

            $lifetime = env('TOKEN_LIFETIME');
            $expired = time() + $lifetime;
            $token = base64_encode(str_random(40));
            User::where('email', $request->input('email'))->update(['token' => $token, 'token_expired' => date('Y-m-d H:i:s', $expired)]);

            return response()->json(['status' => 'success', 'token' => $token, 'token_expired' => $expired]);

        }

        return response()->json(['status' => 'Authentication failed'],401);

    }

    public function signup(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
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
