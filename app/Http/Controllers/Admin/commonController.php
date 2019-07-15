<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class commonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function adminmain($module, $method = null, $optional1 = null, $optional2 = null, $optional3 = null, User $user)
    {
        return view('main.adminmain', ['user' => [
            'name' => $user->name
        ]]);
        
    }

    public function adminpages($pagename)
    {
        // return dump("adminpages.$pagename");
        if (view()->exists("adminpages.$pagename")) {
            return view("adminpages.$pagename");
        } else {
            abort(404);
        }
    }
}
