<?php 
namespace Star\Repositories\Eloquent;

use App\User;
use Illuminate\Support\Facades\Auth;
use Star\Repositories\Contracts\InterfaceUser;
use Star\wechat\WeOpen;

class UserRepo implements InterfaceUser
{
    protected $user;
    
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function hasMobile($mobile)
    {
        return $this->user->where('mobile', $mobile)->first();
    }

    public function saveUser($data)
    {
        return $this->user->create([
                'mobile' => $data['mobile'],
                'password' => bcrypt($data['password'])
            ]);
    }

    public function updateUser($data)
    {
        return $this->user->where('mobile', $data['mobile'])->update(['password' => bcrypt($data['password'])]);
    }

    /**
     * 获取登陆用户全部的微信公众号列表
     * @param  [integer] $uid 用户id
     */
    public function findAllMps()
    {
        $mps = $this->user->wxmps()->get();
        if (count($mps) > 0) {
            return $mps;
        }
        return false;
    }

    public function findMp()
    {
        
    }

    /**
     * 将传递来的数据择入数据库
     * @param  [type] $wxData 微信返回的原始数据
     * @return [type]         [description]
     */
    public function createMp($wxData)
    {
        dd($this->user); 
        $data = json_decode($wxData);
        $appid = $data->authorization_info->authorizer_appid;
        $token = $data->authorizer_info->authorizer_access_token;
        $refreshToken = $data->authorizer_info->authorizer_refresh_token;
        $wxInfo = WeOpen::fetchInfo($appid);
        dd($wxInfo);
    }
}
