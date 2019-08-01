<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topics as Topics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
            'type' => 'required|numeric',
            'answer' => 'max:200',
            'manualverify' => 'boolean'
        ]);

        try {
            $user = Auth::user();
            if (($user->permissions & 1) == 1) {
                $topicRecord = is_null($request->input('id')) ? new Topics : Topics::find($request->input('id'));
                if ($topicRecord) {
                    $topicRecord->level = $request->input('level');
                    $topicRecord->grade = $request->input('grade');
                    $topicRecord->course_id = $request->input('course');
                    $topicRecord->question = $request->input('question');
                    $topicRecord->manualverify = $request->input('manualverify');
                    $topicRecord->type = $request->input('type');
                    $topicRecord->answer = $request->answer;
                    if ($topicRecord->save()) {
                        return $this->successresponse(['id' => $topicRecord->id]);
                    } else {
                        return $this->failureresponse('Save failed');
                    }
                }
            } else {
                return $this->failureresponse('Not allowed!');
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
        $summaryData = [
            ['level' => 'PSCHOOL', 'data' => []],
            ['level' => 'JHSCHOOL', 'data' => []],
            ['level' => 'SHSCHOOL', 'data' => []],
        ];
        try {
            for ($index = 0; $index < count($summaryData); $index++) {
                $summaryRecord = DB::table('topics')
                    ->leftJoin('courses', 'courses.id', '=', 'topics.course_id')
                    ->select('courses.name', 'topics.grade', 'topics.course_id', DB::raw('COUNT(topics.id) AS topics_count'))
                    ->where('topics.level', $summaryData[$index]['level'])
                    ->groupBy('courses.name', 'topics.course_id', 'topics.grade')
                    ->get();
                
                foreach ($summaryRecord as $su) {
                    $summaryData[$index]['data'][] = [
                        'course_name' => $su->name,
                        'course_id' => $su->course_id,
                        'grade' => $su->grade,
                        'topics_count' => $su->topics_count
                    ];
                }
            }

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('topicsController->savetopic->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('topicsController->savetopic->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
        
        return $this->successresponse($summaryData);
    }

    public function topicsList(Request $request)
    {
        $this->validate($request, [
            'level' => 'nullable',
            'grade' => 'nullable',
            'course' => 'nullable',
            'type' => 'nullable',
            'search_content' => 'nullable'
        ]);
        try {
            $topicList = [];
            $topicRecord = DB::table('topics')
                ->leftJoin('courses', 'courses.id', 'topics.course_id')
                ->leftJoin('topictypes', 'topictypes.id', '=', 'topics.type')
                ->select('topics.id', 'topics.question', 'topics.updated_at', 'topics.level', 'topics.grade', 'courses.name', 'topictypes.name AS topic_type')
                ->where(function($query) use ($request) {
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
                        $query->where('topics.question', 'LIKE', '%'.$request->input('searchcontent').'%');
                    }
                    if (!is_null($request->input('type'))) {
                        $query->where('topics.type', $request->input('type'));
                    }
                })
                ->orderBy('topics.updated_at', 'desc')
                ->paginate(20);
            foreach ($topicRecord as $topic) {
                $topicList[] = [
                    'id' => $topic->id,
                    'question' => mb_strimwidth($topic->question, 0, 10, '...'),
                    'question_full' => $topic->question,
                    'level' => $topic->level,
                    'grade' => $topic->grade,
                    'course_name' => $topic->name,
                    'topic_type' => $topic->topic_type,
                    'updated_at' => (new Carbon($topic->updated_at))->locale('zh_CN')->diffForHumans(Carbon::now())
                ];
            }
            return $this->successresponse(['list' => $topicList, 'total' => $topicRecord->total()]);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('topicsController->topicsList->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('topicsController->topicsList->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }

    public function topicDetail($topicId)
    {
        try {
            $topicRecord = Topics::find($topicId);
            if ($topicRecord) {
                return $this->successresponse([
                    'id' => $topicRecord->id,
                    'level' => $topicRecord->level,
                    'grade' => $topicRecord->grade,
                    'course' => $topicRecord->course_id,
                    'question' => $topicRecord->question,
                    'answer' => $topicRecord->answer,
                    'topictype_id' => $topicRecord->type,
                    'manualverify' => $topicRecord->manualverify ? true : false
                ]);
            } else {
                return $this->failureresponse('No record');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('topicsController->topicDetail->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('topicsController->topicDetail->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }
}
