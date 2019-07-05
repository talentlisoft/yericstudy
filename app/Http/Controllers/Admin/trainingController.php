<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class trainingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function trainingsList(Reqeust $request)
    {
        $this->validate($request, [
            'searchcontent' => 'nullable',
        ]);
        try {
            $trainingsRecord = DB::table('trainnings')
                ->select('trainnings.title', 'trainnings.created_at', 'trainnings.id')
                ->paginate(20);
            $trainingsList = [];
            foreach ($trainingsRecord as $tr) {
                $trainingsList[] = [
                    'id' => $tr->id,
                    'title' => $tr->title,
                    'created_at' => (new Carbon($tr->updated_at))->locale('zh_CN')->diffForHumans(Carbon::now()),
                ];
            }
            return $this->successresponse(['list' => $trainingsList, 'total' => $trainingsRecord->total()]);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('trainingController->trainingsList->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('trainingController->trainingsList->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }

    public function gettopicsList(Request $request)
    {
        $this->validate($request, [
            'level' => 'nullable',
            'grade' => 'nullable',
            'course' => 'nullable',
            'searchcontent' => 'nullable',
            'mode' => 'required',
        ]);

        try {
            switch ($request->input('mode')) {
                case 'RADOM':
                    $topicRecord = DB::table('topics')
                        ->leftJoin('courses', 'courses.id', 'topics.course_id')
                        ->select('topics.id', 'topics.question', 'topics.updated_at', 'topics.level', 'topics.grade', 'courses.name')
                        ->where(function ($query) use ($request) {
                            if (!is_null($request->input('level'))) {
                                $query->where('topics.level', $request->input('level'));
                            }
                            if (!is_null($request->input('grade'))) {
                                $query->where('topics.grade', $request->input('grade'));
                            }
                            if (!is_null($request->input('course'))) {
                                $query->where('topics.course_id', $request->input('course'));
                            }
                            if (!is_null($request->input('searchcontent'))) {
                                $query->where('topics.question', 'LIKE', '%' . $request->input('searchcontent') . '%');
                            }
                        })
                        ->inRandomOrder()
                        ->paginate(20);
                    break;
                default:
                        return $this->successresponse(['list' => [], 'total' => 0]);
            }

            $topicList = [];
            foreach ($topicRecord as $to) {
                $topicList[] = [
                    'id' => $topic->id,
                    'question' => mb_strimwidth($topic->question, 0, 10, '...'),
                    'level' => $topic->level,
                    'grade' => $topic->grade,
                    'course_name' => $topic->name,
                    'updated_at' => (new Carbon($topic->updated_at))->locale('zh_CN')->diffForHumans(Carbon::now())
                ];
            }
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('trainingController->gettopicsList->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('trainingController->gettopicsList->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }
}
