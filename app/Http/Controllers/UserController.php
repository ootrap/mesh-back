<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
         return User::all();
    }
}
