<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Hash;

class authController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'email'=> 'required',
            'password' => 'required'
        ]);
        try {
            $userRecord = DB::table('users')->where('email', $request->input('email'))->first();
            if ($userRecord) {
                if (Hash::check($request->input('password'), $userRecord->password)) {
                    return $this->successresponse(['name' => $userRecord->name, 'api_token' =>$userRecord->api_token]);
                }
            }
            return $this->failureresponse('Email address and password mismatch!');
            
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('api\authController->login->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('api\authController->login->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }
}
