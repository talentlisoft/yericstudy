<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\trainingbaseController;

class apitraineeController extends trainingbaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
}
