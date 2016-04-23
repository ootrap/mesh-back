<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use Hash;
use Illuminate\Http\Request;
use JWTAuth;

class APIController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['login']]);
    }

    public function register(Request $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        User::create($input);
        return response()->json([
          'code' => 200,
          'result' => true
          ]);
    }

    public function login(Request $request)
    {

           $credentials = $request->only('name', 'password');
        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['result' => '手机号或密码错误'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['result' => '服务器内部错误'], 500);
        }
        // if no errors are encountered we can return a JWT
         return response()->json([
          'result' => $token
          ], 200);
    }

    public function getUserDetails()
    {
        return User::all();
    }
}
