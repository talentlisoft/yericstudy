<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\usersController;

class webusersController extends usersController
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }
}
