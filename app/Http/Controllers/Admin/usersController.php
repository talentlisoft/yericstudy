<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
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
            DB::beginTransaction();
            $userRecord = is_null($request->input('id')) ? new User : User::find($request->input('id'));
            if ($userRecord) {
                $userRecord->name = $request->input('name');
                $userRecord->email = $request->input('email');
                if ($request->input('password') != 'nochange') {
                    $userRecord->password =  Hash::make($request->input('password'));
                }
                $userRecord->permissions = ($request->input('permissions.checktopics') ? 1 :0) | ($request->input('permissions.checkusers') ? 2 : 0);
                if ($userRecord->save())
                {
                    DB::table('user_trainee')->where('user_id', $userRecord->id)->delete();
                    foreach ($request->input('selectedtrainees') as $trainee) {
                        DB::table('user_trainee')
                            ->insert([
                              'user_id' => $userRecord->id,
                              'trainee_id'=>  $trainee['id'],
                              'created_at' => Carbon::now(),
                              'updated_at' => Carbon::now()
                            ]);
                    }
                }
                DB::commit();
                return $this->successresponse(['id' => $userRecord->id]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Log::error('usersController->saveuser->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('usersController->saveuser->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }

    public function userDetail($userId)
    {
        try {
            $userRecord = User::find($userId);
            if ($userRecord) {
                $userData = [
                    'id' => $userRecord->id,
                    'name' => $userRecord->name,
                    'email' => $userRecord->email,
                    'password' => 'nochange',
                    'permissions' => ['checktopics' => ($userRecord->permissions & 1) == 1 ? true : false, 'checkusers' => ($userRecord->permissions & 2) == 2 ? true : false],
                    'selectedtrainees' => []
                ];
                $traineeRecord = DB::table('user_trainee')
                    ->select('trainee_id')
                    ->where('user_id', $userId)
                    ->get();
                foreach ($traineeRecord as $trainee) {
                    $userData['selectedtrainees'][] = ['id' => $trainee->trainee_id];
                }
                return $this->successresponse($userData);
            } else {
                return $this->failureresponse('Record not exists!');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('usersController->userDetail->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('usersController->userDetail->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }
}
