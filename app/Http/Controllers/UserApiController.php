<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PingCheng\ApiResponse\ApiResponse;

class UserApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('api');
        $this->middleware('auth');
    }

    public function info() {
        $user = auth()->user();
        return ApiResponse::ok([
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'email' => $user->email,
        ]);
    }
}
