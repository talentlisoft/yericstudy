<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\User;

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

    public function saveuser(Request $request)
    {
        $this->validate($request, [
            'id' => 'nullable',
            'name' => 'required|max:20',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'permissions.checktopics' => 'required|boolean',
            'permissions.checkusers' => 'required|boolean',
            'selectedtrainees' => 'array',
            'selectedtrainees.*.id' => 'numeric|required'
        ]);

        try {
            $userRecord = is_null($request->input('id')) ? new User : User::find($request->input('id'));
            if ($userRecord) {
                $userRecord->name = $request->input('name');
                $userRecord->email = $request->input('email');
                $userRecord->password = Hash::make($request->input('password'));
                
            }
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('usersController->saveuser->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('usersController->saveuser->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }
}
