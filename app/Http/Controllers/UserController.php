<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Star\Repositories\Eloquent\UserRepo;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }

    public function mplist()
    {
        return $this->user->findAllMps(Auth::user());
    }
}
