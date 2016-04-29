<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Star\Forms\AuthFormRequest;
use Star\Forms\LoginFormRequest;
use Star\Forms\SignUpFormRequest;

class AuthController extends Controller
{

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * 用户登陆
     * @param  LoginFormRequest $request
     * @return [json]                返回登陆json数据
     */
    public function login(LoginFormRequest $request)
    {
        return $request->login();
    }

    /**
     * 刷新token
     */
    public function refreshToken()
    {
        return LoginFormRequest::refreshToken();
    }

    /**
     * 用户注册
     * @param  SignUpFormRequest $request [description]
     * @return [type]                     [description]
     */
    public function create(SignUpFormRequest $request)
    {
        return $request->persist();
    }
}
