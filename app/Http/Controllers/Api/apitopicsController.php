<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Admin\topicsBaseController;

class apitopicsController extends topicsBaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
}
