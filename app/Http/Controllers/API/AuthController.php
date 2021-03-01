<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'email' => 'required',
                'password' => 'required',
            ]
        );
        if ($validation->fails()) {
            return response()->json($validation->errors()->first(), 402);
        }
        //Check valid credentials
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json('These credentials do not match our records', 404);
        }
        //Create user access token
        $token = $user->createToken('sanctum')->plainTextToken;

        return response()->json($token);
    }

    public function register(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:20|min:3',
                'email' => 'required|unique:users',
                'password' => 'required|string|min:6',
                'retyped_password' => 'required|string|same:password'
            ]
        );
        if ($validation->fails()) {
            return response()->json($validation->errors()->first(), 402);
        }
        //Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        //Create user access token
        $token = $user->createToken('sanctum')->plainTextToken;

        return response()->json($token);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json("Success");
    }

    public function user(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ]);
    }
}
