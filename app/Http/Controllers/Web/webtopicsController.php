<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\topicsBaseController;

class webtopicsController extends topicsBaseController
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }
}
