<?php

namespace App\Http\Controllers\Admin;

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
        $topictypes = [];
        try {
            $coursesRecord = DB::table('courses')
                ->select('courses.id', 'courses.name')
                ->get();
            $topictypesRecord = DB::table('topictypes')
                ->select('topictypes.id', 'topictypes.name')
                ->get();
            foreach ($coursesRecord as $course) {
                $coursesList[] = [
                    'id' => $course->id,
                    'name' => $course->name,
                ];
            }

            foreach ($topictypesRecord as $key => $type) {
                $topictypes[] = [
                    'id' => $type->id,
                    'name' => $type->name,
                ];
            }
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('coursesController->getcoursesList->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('coursesController->getcoursesList->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
        return $this->successresponse(['courses' => $coursesList, 'topic_types' => $topictypes]);
    }

}
