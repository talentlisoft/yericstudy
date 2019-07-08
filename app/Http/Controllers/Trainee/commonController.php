<?php

namespace App\Http\Controllers\Trainee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Trainee;

class commonController extends Controller
{
    public function __construct()
    {
        $this->middleware('traineeauth');
    }

    public function showtraineemainpage($module, $method = null, $optional1 = null, $optional2 = null, $optional3 = null, Trainee $trainee)
    {
        return view('main.traineemain', ['user' => ['name' => $trainee->name]]);
    }

    public function traineepages($pagename)
    {
        // return dump("adminpages.$pagename");
        if (view()->exists("traineepages.$pagename")) {
            return view("traineepages.$pagename");
        } else {
            abort(404);
        }
    }
}
