<?php 
namespace Star\Repositories\Eloquent;

use App\User;
use Illuminate\Support\Facades\Auth;
use Star\Repositories\Contracts\InterfaceUser;

class UserRepo implements InterfaceUser
{
    protected $user;

    public function __construct()
    {
        $this->user = new User;
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
        $user = $this->user->find(Auth::user()->id);
        $mps = $user->wxmps()->get();
        if (count($mps) > 0) {
            return $mps;
        }
        return response()->json(['result' => null]);
    }

    public function findMpById($id)
    {
        
    }

    public function createMp($wxData)
    {
        $name = $wxData->authorizer_info->user_name;
        dd($name);
    }
}
