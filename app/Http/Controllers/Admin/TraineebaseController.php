<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Trainee;

class TraineebaseController extends Controller
{
    public function traineeList()
    {
        try {
            $traineeRecord = DB::table('trainees')
                ->select('name', 'id', 'avatar')
                ->get();
            $traineeList = [];
            foreach ($traineeRecord as $trainee) {
                $traineeList[] = [
                    'id' => $trainee->id,
                    'name'=> $trainee->name,
                    'avatar' => $trainee->avatar
                ];
            }

            return $this->successresponse($traineeList);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('TraineeController->traineeList->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('TraineeController->traineeList->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    
    }

    public function savetrainee(Request $request)
    {
        $this->validate($request, [
            'id' => 'nullable',
            'name' => 'required|max:10',
            'password' => 'required',
            'avatar' => 'required|max:200'
        ]);

        try {
            $user = Auth::user();
            if (($user->permissions & 2) == 2) {
                $traineeRecord = is_null($request->input('id')) ? new Trainee : Trainee::find($request->input('id'));
                if ($traineeRecord) {
                    $traineeRecord->name = $request->input('name');
                    $traineeRecord->avatar = $request->input('avatar');
                    $traineeRecord->password = (is_null($request->input('id')) || $request->input('password') != 'nochange') ? Hash::make($request->input('password')) : $traineeRecord->password;
                    if ($traineeRecord->save()) {
                        return $this->successresponse([
                            'id' => $traineeRecord->id
                        ]);
                    } else {
                        return $this->failureresponse('Save failed!');
                    }
                } else {
                    return $this->failureresponse('No record!');
                }
            } else {
                return $this->failureresponse('Not allowed');
            }

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('TraineeController->savetrainee->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('TraineeController->savetrainee->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }
}
