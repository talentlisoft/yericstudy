<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class usersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function userslist()
    {
        try {
            $usersRecord = DB::table('users')
                ->select('id', 'name', 'email')
                ->get();
            $userList = [];
            foreach ($usersRecord as $user) {
                $userList[] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email'=> $user->email
                ];
            }

            return $this->successresponse($userList);

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('usersController->userslist->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('usersController->userslist->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }
}
