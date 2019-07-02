<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Topics as Topics;

class topicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function savetopic(Request $request)
    {
        $this->validate($request, [
            'id' => 'nullable',
            'level' => 'required',
            'grade' => 'required',
            'course' => 'required',
            'question' => 'required',
            'answer' => 'required|max:50',
        ]);

        try {
            $topicRecord = is_null($request->input('id')) ? new Topics : Topics::find($request->input('id'));
            if ($topicRecord) {
                $topicRecord->level = $request->input('level');
                $topicRecord->grade = $request->input('grade');
                $topicRecord->course_id = $request->input('course');
                $topicRecord->question = $request->input('question');
                $topicRecord->answer = $request->answer;
                if ($topicRecord->save()) {
                    return $this->successresponse(['id' => $topicRecord->id]);
                } else {
                    return $this->failureresponse('Save failed');
                }
            }
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('topicsController->savetopic->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('topicsController->savetopic->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }

    public function summary()
    {
        try {
            $summaryRecord = DB::table('topics')
                ->leftJoin('courses', 'courses.id', '=', 'topics.course_id')
                ->select('courses.name', 'topics.grade', DB::raw('COUNT(topics.id) AS topics_count'))
                ->groupBy('courses.name', 'topics.grade')
                ->get();
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('topicsController->savetopic->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('topicsController->savetopic->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }
}
