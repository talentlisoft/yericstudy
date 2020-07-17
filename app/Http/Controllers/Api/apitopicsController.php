<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\topicsBaseController;

class apitopicsController extends topicsBaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
        parent::__construct();
    }
}
