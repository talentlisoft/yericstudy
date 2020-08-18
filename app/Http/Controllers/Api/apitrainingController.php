<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\trainingbaseController;
use Illuminate\Support\Facades\Auth;

class apitrainingController extends trainingbaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getUser() {
        return Auth::guard('api')->user();
    }
}
