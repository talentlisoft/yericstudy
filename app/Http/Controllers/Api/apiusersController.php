<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\usersController;
use Illuminate\Support\Facades\Auth;

class apiusersController extends usersController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function userinfo()
    {
        return $this->successresponse(Auth::user());
    }
}
