<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class coursesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getcoursesList()
    {
        $coursesList = [];
        try {
            $coursesRecord = DB::table('courses')
                ->select('courses.id', 'courses.name')
                ->get();
            foreach ($coursesRecord as $course) {
                $coursesList[] = [
                    'id' => $course->id,
                    'name' => $course->name
                ];
            }
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('coursesController->getcoursesList->QueryException异常'.$e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('coursesController->getcoursesList->Exception'.$e->getMessage());
            return $this->failureresponse('操作失败.');
        }
        return $this->successresponse($coursesList);
    }

}
