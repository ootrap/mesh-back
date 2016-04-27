<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Star\Repositories\Eloquent\UserRepo;

class UserController extends Controller
{
    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }

    public function mps()
    {
        return $this->user->getWxmpsById(Auth::user()->id);
    }
}
