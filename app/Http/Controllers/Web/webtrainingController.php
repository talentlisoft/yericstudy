<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\trainingbaseController;

class webtrainingController extends trainingbaseController
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }
}
