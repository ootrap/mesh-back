<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use Hash;
use Illuminate\Http\Request;
use JWTAuth;

class APIController extends Controller
{
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
        $input = $request->all();
        if (!$token = JWTAuth::attempt($input)) {
            return response()->json([
              'code' => 404,
              'result' => '手机号或密码错误'
              ]);
        }
        return response()->json([
          'code' => 200,
          'result' => $token
          ]);
    }

    public function getUserDetails(Request $request)
    {
        $input = $request->all();
        $user = JWTAuth::toUser($input['token']);
        return response()->json(['result' => $user]);
    }
}
