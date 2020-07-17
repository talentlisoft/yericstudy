<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\coursesbaseController;

class apicoursesController extends coursesbaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
}
