<?php 
namespace Star\Repositories\Eloquent;

use App\User;
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

    public function getWxmpsById($uid)
    {
        $user = $this->user->find($uid);
        return $user->wxmps()->get();
    }
}
