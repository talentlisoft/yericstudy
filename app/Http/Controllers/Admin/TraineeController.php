<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TraineeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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
}
