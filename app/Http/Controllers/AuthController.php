<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use JWTAuth;
use Star\sms\proxy\BechSmsProxy;
use Star\utils\RandomNum;
use Validator;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use ThrottlesLogins;

    use RandomNum;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    // protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', ['only' => 'test']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'mobile' => 'required|max:11',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function register(array $data)
    {
        User::create([
            'mobile' => $data['mobile'],
            'password' => bcrypt($data['password']),
        ]);
        return response()->json(['result'=>true], 200);
    }

    protected function authenticateLogin(Request $request)
    {
           $credentials = $request->only('mobile', 'password');
           $this->validate($request, [
                'mobile'=>'required|integer|digits: 11'
            ]);
        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['result' => ['手机号或密码错误']], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['result' => ['服务器内部错误']], 500);
        }
        // if no errors are encountered we can return a JWT
         return response()->json([
          'result' => $token
          ], 200);
    }

    protected function create(Request $request)
    {
        $this->validate($request, [
                'mobile'=>'required|integer|digits: 11',
                'pass'=>'required|min:6|confirmed',
                'pass_confirmation'=>'required|min:6',
                'authCode'=>'required'
            ]);
        try {
            if (User::where('mobile', '=', $request['mobile'])->first()) {
                return response()->json(['result' => ['该手机号已经注册，请直接登陆']], 403);
            } elseif ($request['authCode'] !== Cache::get($request['mobile'])) {
                return response()->json(['result' => ['短信验证码填写错误']], 403);
            }
        } catch (Exception $e) {
            return response()->json(['result' => [$e]], 500);
        }

        $create = User::create([
                'mobile'=>$request['mobile'],
                'password'=>bcrypt($request['pass'])
            ]);
        if ($create) {
            return response()->json(['result' => ['注册成功，请登陆']], 200);
        }
    }

    public function findPassword(Request $request)
    {
        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            if ($request->ajax()) {
                return response()->json(['lockout_time' => $this->lockoutTime()]);
            } else {
                return $this->sendLockoutResponse($request);
            }
        }
    }

    public function sendSms(Request $request)
    {
        $mobile = $request->only('mobile')['mobile'];
        $code = $this->randomNum(6);
        Cache::put($mobile, $code);
        $pattern = '/{\w+}/';
        $content = preg_replace($pattern, $code, \Config::get('sms.Templates.authcode'));
        $fire = new BechSmsProxy($mobile, $content);
        return $fire->fire();
    }
    


    public function test()
    {
        return User::all();
    }
}
