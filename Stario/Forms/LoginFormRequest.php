<?php

namespace Star\Forms;

use App\Http\Requests\Request;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginFormRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'mobile' => 'required',
            'password' => 'required|min:6'
        ];
    }
    public function login()
    {
            $request = $this->request->all();
            $credentials = ['mobile'=>$request['mobile'],'password'=>$request['password']];
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['result' => ['手机号或密码错误']], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['result' => ['服务器内部错误']], 500);
        }
        // 返回$token供前端使用
         return response()->json([
          'result' => $token
          ], 200);
    }
}
