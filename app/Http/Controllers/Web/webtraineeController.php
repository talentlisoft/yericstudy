<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\TraineebaseController;

class webtraineeController extends TraineebaseController
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }
}
