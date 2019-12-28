<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\coursesbaseController;

class webcoursesController extends coursesbaseController
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }
}
