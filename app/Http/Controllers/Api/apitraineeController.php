<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\TraineebaseController;

class apitraineeController extends TraineebaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
}
