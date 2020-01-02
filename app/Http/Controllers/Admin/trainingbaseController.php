<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Training as Training;
use App\Models\TrainingTopics as TrainingTopics;
use App\Models\TrainingTrainees as TrainingTrainees;
use Illuminate\Support\Facades\Auth;
use App\Traits\traineetopicssummaryUpdater;
use App\User;

class trainingbaseController extends Controller
{
    use traineetopicssummaryUpdater;

    public function trainingsList(Request $request)
    {
        $this->validate($request, [
            'searchcontent' => 'nullable',
        ]);
        try {
            $user = Auth::user();
            $trainingsRecord = DB::table('trainnings')
                ->select('trainnings.title', 'trainnings.created_at', 'trainnings.id', 
                DB::raw('COUNT(trainee_trainings.id) AS trainee_count'),
                DB::raw('SUM(IF(trainee_trainings.status = 1, 1, 0)) AS finished_count')
                )
                ->leftJoin('trainee_trainings', 'trainee_trainings.training_id', '=', 'trainnings.id')
                ->join('user_trainee', 'user_trainee.trainee_id', '=' ,'trainee_trainings.trainee_id')
                ->where('user_trainee.user_id', $user->id)
                ->orderBy('trainnings.created_at', 'desc')
                ->groupBy('trainnings.title', 'trainnings.created_at', 'trainnings.id')
                ->paginate(20);
            $trainingsList = [];
            foreach ($trainingsRecord as $tr) {
                $trainingsList[] = [
                    'id' => $tr->id,
                    'title' => $tr->title,
                    'trainee_count' =>$tr->trainee_count,
                    'finished_count' => $tr->finished_count,
                    'created_at' => (new Carbon($tr->created_at))->locale('zh_CN')->diffForHumans(Carbon::now()),
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

    public function savetraining(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:50',
            'trainees' => 'array|required',
            'trainees.*.id' => 'numeric|required',
            'topics' => 'array|required',
            'topics.*.id' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();
            $trainingRecord = new Training;
            $trainingRecord->title = $request->input('title');
            if ($trainingRecord->save()) {
                // save training topics
                foreach ($request->input('topics') as $topic) {
                    $trainingtopicRecord = new TrainingTopics;
                    $trainingtopicRecord->training_id = $trainingRecord->id;
                    $trainingtopicRecord->topic_id = $topic['id'];
                    $trainingtopicRecord->save();
                }

                // save training trainees
                foreach ($request->input('trainees') as $trainee) {
                    $ttrecord = new TrainingTrainees;
                    $ttrecord->trainee_id = $trainee['id'];
                    $ttrecord->training_id = $trainingRecord->id;
                    $ttrecord->status = 0; // init status
                    $ttrecord->save();
                }
            }
            DB::commit();
            return $this->successresponse(['id' => $trainingRecord->id]);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('trainingController->trainingsList->QueryException异常' . $e->getMessage());
            DB::rollBack();
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
            'type' => 'nullable',
            'trainees' => 'array'
        ]);

        try {
            $user = $request->user();
            switch ($request->input('mode')) {
                case 'RADOM':
                    $topicRecord = DB::table('topics')
                        ->leftJoin('courses', 'courses.id', 'topics.course_id')
                        ->leftJoin('topictypes', 'topictypes.id', '=', 'topics.type')
                        ->leftJoin('trainee_topics_summary', function($join) use ($request) {
                            $join->on('trainee_topics_summary.topic_id', '=', 'topics.id');
                            if (!empty($request->input('trainees'))) {
                                $join->whereIn('trainee_topics_summary.trainee_id', $request->input('trainees'));
                            }
                        })
                        ->leftJoin('user_trainee', function($join) use ($user) {
                            $join->on('user_trainee.trainee_id', '=', 'trainee_topics_summary.trainee_id');
                            $join->on('user_trainee.user_id', '=', DB::raw($user->id));
                        })
                        ->select('topics.id', 'topics.question', 'topics.updated_at', 'topics.level', 'topics.grade', 'courses.name', 'topictypes.name AS topic_type', DB::raw('SUM(trainee_topics_summary.correct_count) AS total_correct'), DB::raw('SUM(trainee_topics_summary.fail_count) AS total_fail'))
                        ->groupBy('topics.id', 'topics.question', 'topics.updated_at', 'topics.level', 'topics.grade', 'courses.name', 'topictypes.name')
                        ->where(function ($query) use ($request) {
                            // if (!empty($request->input('trainees'))) {
                            //     $query->whereIn('trainee_topics_summary.trainee_id', $request->input('trainees'));
                            //     $query->orWhere('trainee_topics_summary.trainee_id', null);
                            // }
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
                            if (!is_null($request->input('type'))) {
                                $query->where('topics.type', $request->input('type'));
                            }
                        })
                        ->inRandomOrder()
                        ->paginate(20);
                    break;
                case 'RECENT':
                    $topicRecord = DB::table('trainee_topics_summary')
                        ->join('topics', 'topics.id', 'trainee_topics_summary.topic_id')
                        ->leftJoin('topictypes', 'topictypes.id', '=', 'topics.type')
                        ->join('user_trainee', 'user_trainee.trainee_id', '=', 'trainee_topics_summary.trainee_id')
                        ->leftJoin('courses', 'courses.id', 'topics.course_id')
                        ->where('user_trainee.user_id', $user->id)
                        ->where(function($query) use ($request) {
                            if (!empty($request->input('trainees'))) {
                                $query->whereIn('trainee_topics_summary.trainee_id', $request->input('trainees'));
                            }
                        })
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
                            if (!is_null($request->input('type'))) {
                                $query->where('topics.type', $request->input('type'));
                            }
                        })
                        ->where('trainee_topics_summary.recent_failed', 1)
                        ->select('topics.id', 'topics.question', 'topics.updated_at', 'topics.level', 'topics.grade', 'courses.name', 'topictypes.name AS topic_type', DB::raw('SUM(trainee_topics_summary.correct_count) AS total_correct'), DB::raw('SUM(trainee_topics_summary.fail_count) AS total_fail'))
                        ->groupBy('topics.id', 'topics.question', 'topics.updated_at', 'topics.level', 'topics.grade', 'courses.name', 'topictypes.name')
                        ->paginate(20);
                    break;
                case 'EVER':
                    $topicRecord = DB::table('trainee_topics_summary')
                        ->join('user_trainee', 'user_trainee.trainee_id', '=', 'trainee_topics_summary.trainee_id')
                        ->join('topics', 'topics.id', 'trainee_topics_summary.topic_id')
                        ->leftJoin('topictypes', 'topictypes.id', '=', 'topics.type')
                        ->leftJoin('courses', 'courses.id', 'topics.course_id')
                        ->where('user_trainee.user_id', $user->id)
                        ->where(function($query) use ($request) {
                            if (!empty($request->input('trainees'))) {
                                $query->whereIn('trainee_topics_summary.trainee_id', $request->input('trainees'));
                            }
                        })
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
                            if (!is_null($request->input('type'))) {
                                $query->where('topics.type', $request->input('type'));
                            }
                        })
                        ->where('trainee_topics_summary.recent_failed', 0)
                        ->where('trainee_topics_summary.fail_count', '>', 0)
                        ->select('topics.id', 'topics.question', 'topics.updated_at', 'topics.level', 'topics.grade', 'courses.name', 'topictypes.name AS topic_type', DB::raw('SUM(trainee_topics_summary.correct_count) AS total_correct'), DB::raw('SUM(trainee_topics_summary.fail_count) AS total_fail'))
                        ->orderBy(DB::raw('(SUM( trainee_topics_summary.fail_count ) - SUM( trainee_topics_summary.correct_count ))'), 'desc')
                        ->groupBy('topics.id', 'topics.question', 'topics.updated_at', 'topics.level', 'topics.grade', 'courses.name', 'topictypes.name')
                        ->paginate(20);
                    break;
                case 'FREQUENCY':
                    $topicRecord = DB::table('topics')
                        ->leftJoin('topictypes', 'topictypes.id', '=', 'topics.type')
                        ->leftJoin('trainee_topics_summary', function($join) use ($request) {
                            $join->on('trainee_topics_summary.topic_id', '=', 'topics.id');
                            if (!empty($request->input('trainees'))) {
                                $join->whereIn('trainee_topics_summary.trainee_id', $request->input('trainees'));
                            }
                        })
                        ->leftJoin('user_trainee', function($join) use ($user) {
                            $join->on('user_trainee.trainee_id', '=', 'trainee_topics_summary.trainee_id');
                            $join->on('user_trainee.user_id', '=', DB::raw($user->id));
                        })
                        ->leftJoin('training_results', function($join) use ($request) {
                            $join->on('training_results.trainingtopic_id', '=', 'topics.id');
                        })
                        ->leftJoin('courses', 'courses.id', '=','topics.course_id')
                        ->leftJoin('trainee_trainings', function($join) use ($request) {
                            $join->on('trainee_trainings.id', '=', 'training_results.trainingtrainee_id');
                            if (!empty($request->input('trainees'))) {
                                $join->whereIn('trainee_trainings.trainee_id', $request->input('trainees'));
                            }
                        })
                        ->select('topics.id', 'topics.question', 'topics.updated_at', 'topics.level', 'topics.grade', 'courses.name', 'topictypes.name AS topic_type', DB::raw('COUNT(training_results.id) AS training_count'), DB::raw('SUM(trainee_topics_summary.correct_count) AS total_correct'), DB::raw('SUM(trainee_topics_summary.fail_count) AS total_fail'))
                        // ->where(function($query) use ($request) {
                        //     if (!empty($request->input('trainees'))) {
                        //         $query->whereIn('trainee_trainings.trainee_id', $request->input('trainees'));
                        //         $query->orWhereNull('training_results.id');
                        //     }
                        // })
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
                            if (!is_null($request->input('type'))) {
                                $query->where('topics.type', $request->input('type'));
                            }
                        })
                        ->groupBy('topics.id', 'topics.question', 'topics.updated_at', 'topics.level', 'topics.grade', 'courses.name', 'topictypes.name')
                        ->orderBy(DB::raw('COUNT(training_results.id)'))
                        ->paginate(20);
                    break;
                case 'NEWEST':
                    $topicRecord = DB::table('topics')
                        ->leftJoin('courses', 'courses.id', 'topics.course_id')
                        ->leftJoin('topictypes', 'topictypes.id', '=', 'topics.type')
                        ->leftJoin('trainee_topics_summary', function($join) use ($request) {
                            $join->on('trainee_topics_summary.topic_id', '=', 'topics.id');
                            if (!empty($request->input('trainees'))) {
                                $join->whereIn('trainee_topics_summary.trainee_id', $request->input('trainees'));
                            }
                        })
                        ->leftJoin('user_trainee', function($join) use ($user) {
                            $join->on('user_trainee.trainee_id', '=', 'trainee_topics_summary.trainee_id');
                            $join->on('user_trainee.user_id', '=', DB::raw($user->id));
                        })
                        ->select('topics.id', 'topics.question', 'topics.updated_at', 'topics.level', 'topics.grade', 'courses.name', 'topictypes.name AS topic_type', DB::raw('SUM(trainee_topics_summary.correct_count) AS total_correct'), DB::raw('SUM(trainee_topics_summary.fail_count) AS total_fail'))
                        ->groupBy('topics.id', 'topics.question', 'topics.updated_at', 'topics.level', 'topics.grade', 'courses.name', 'topictypes.name')
                        ->where(function ($query) use ($request) {
                            // if (!empty($request->input('trainees'))) {
                            //     $query->whereIn('trainee_topics_summary.trainee_id', $request->input('trainees'));
                            //     $query->orWhere('trainee_topics_summary.trainee_id', null);
                            // }
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
                            if (!is_null($request->input('type'))) {
                                $query->where('topics.type', $request->input('type'));
                            }
                        })
                        ->orderBy('topics.updated_at', 'desc')
                        ->paginate(20);
                    break;
                default:
                        return $this->successresponse(['list' => [], 'total' => 0]);
            }

            $topicList = [];
            foreach ($topicRecord as $to) {
                $topicList[] = [
                    'id' => $to->id,
                    'question' => mb_strimwidth($to->question, 0, 10, '...'),
                    'question_full' => $to->question,
                    'level' => $to->level,
                    'grade' => $to->grade,
                    'course_name' => $to->name,
                    'topic_type' => $to->topic_type,
                    'total_correct' => ($request->input('mode') == 'FREQUENCY' && $to->training_count >0) ? ($to->total_correct / $to->training_count) : $to->total_correct,
                    'total_fail' => ($request->input('mode') == 'FREQUENCY' && $to->training_count >0) ? ($to->total_fail / $to->training_count) : $to->total_fail,
                    'updated_at' => (new Carbon($to->updated_at))->locale('zh_CN')->diffForHumans(Carbon::now())
                ];
            }

            return $this->successresponse(['list' => $topicList, 'total' => $topicRecord->total()]);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('trainingController->gettopicsList->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('trainingController->gettopicsList->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }

    public function trainingDetail($trainingId)
    {
        try {
            $user = Auth::user();
            $traineeRecord = DB::table('trainee_trainings')
                ->join('user_trainee', 'user_trainee.trainee_id', '=' ,'trainee_trainings.trainee_id')
                ->join('trainees', 'trainees.id', '=', 'trainee_trainings.trainee_id')
                ->select('trainees.name', 'trainee_trainings.id', 
                    DB::raw('(SELECT COUNT(*) FROM training_topics WHERE training_topics.training_id = trainee_trainings.training_id) AS total_topics'), 
                    DB::raw('(SELECT COUNT(*) FROM training_results WHERE training_results.trainingtrainee_id = trainee_trainings.id) AS finished_topics'), 
                    'trainee_trainings.status')
                ->where('trainee_trainings.training_id', $trainingId)
                ->where('user_trainee.user_id', $user->id)
                ->get();

            $traineeList = [];
            foreach ($traineeRecord as $trainee) {
                $traineeList[] = [
                    'traineetraining_id' => $trainee->id,
                    'trainee_name' => $trainee->name,
                    'total_topics' => $trainee->total_topics,
                    'finished_topics' => $trainee->finished_topics,
                    'status' => $trainee->status
                ];
            }
            return $this->successresponse($traineeList);

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('trainingController->gettopicsList->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('trainingController->gettopicsList->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }

    public function trainingResult($traineetrainingId)
    {
        try {
            $user = Auth::user();
            $trainingRecord = DB::table('trainee_trainings')
                ->join('user_trainee', 'user_trainee.trainee_id', '=' ,'trainee_trainings.trainee_id')
                ->join('trainnings', 'trainnings.id', '=', 'trainee_trainings.training_id')
                ->select('trainnings.title', 'trainee_trainings.created_at', 'trainee_trainings.training_id', 'trainee_trainings.status')
                ->where('trainee_trainings.id', $traineetrainingId)
                ->where('user_trainee.user_id', $user->id)
                ->first();
            if ($trainingRecord) {
                $trainingresult = DB::table('training_topics')
                    ->join('topics', 'topics.id', '=', 'training_topics.topic_id')
                    ->leftJoin('topictypes', 'topictypes.id', '=', 'topics.type')
                    ->leftJoin('training_results', function($join) use ( $traineetrainingId) {
                        $join->on('training_results.trainingtrainee_id', '=', DB::raw($traineetrainingId));
                        $join->on('training_results.trainingtopic_id', '=', 'training_topics.topic_id');
                    })
                    ->leftJoin('trainee_trainings', 'trainee_trainings.id', 'training_results.trainingtrainee_id')
                    ->leftJoin('trainee_topics_summary', function($join) {
                        $join->on('trainee_topics_summary.topic_id', '=', 'topics.id');
                        $join->on('trainee_topics_summary.trainee_id', '=', 'trainee_trainings.trainee_id');
                    })
                    ->select('topics.question', 'topics.id as topic_id', 'training_results.answer', 'training_results.status', 'training_results.duration', 'topictypes.name as topic_type', 'training_results.id', 'trainee_topics_summary.correct_count', 'trainee_topics_summary.fail_count')
                    ->where('training_topics.training_id', $trainingRecord->training_id)
                    ->get();

                $resultList = [];
                $correctCount = 0;
                foreach ($trainingresult as $key => $result) {
                    $resultList[] = [
                        'question' => mb_strimwidth($result->question, 0, 10, '...'),
                        'topic_id' => $result->topic_id,
                        'answer' => $result->answer ?? '--',
                        'status' => $result->status,
                        'duration' => $result->duration ?? '--',
                        'topic_type' => $result->topic_type,
                        'result_id' => $result->id,
                        'correct_count' => $result->correct_count,
                        'fail_count' => $result->fail_count
                    ];
                    $correctCount += ($result->status == 'CORRECT' ? 1 : 0);
                }
                return $this->successresponse([
                    'id' => $traineetrainingId,
                    'title' => $trainingRecord->title,
                    'created_at' => (new Carbon($trainingRecord->created_at))->locale('zh_CN')->diffForHumans(Carbon::now()),
                    'results' => $resultList,
                    'status' => $trainingRecord->status,
                    'score' => round(($correctCount / count($trainingresult)) * 100)
                ]);
            } else {
                return $this->failureresponse('Can not find such training');
            }

            
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('trainingController->trainingResult->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('trainingController->trainingResult->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }

    public function manualauditlist()
    {
        try {
            $user = Auth::user();
            $manualauditRecord = DB::table('training_results')
                ->join('topics', 'topics.id', '=', 'training_results.trainingtopic_id')
                ->join('trainee_trainings', 'trainee_trainings.id', 'training_results.trainingtrainee_id')
                ->join('user_trainee', 'user_trainee.trainee_id', '=' ,'trainee_trainings.trainee_id')
                ->join('trainees', 'trainees.id', '=', 'trainee_trainings.trainee_id')
                ->select('trainees.name as trainee_name', 'topics.question', 'training_results.answer', 'training_results.id as result_id')
                ->where('training_results.status', 'PENDDING')
                ->where('user_trainee.user_id', $user->id)
                ->get();
            
            $manualauditList = [];
            foreach ($manualauditRecord as $au) {
                $manualauditList[] = [
                    'result_id' => $au->result_id,
                    'trainee_name' => $au->trainee_name,
                    'question' => mb_strimwidth($au->question, 0, 10, '...'),
                    'answer' => mb_strimwidth($au->answer, 0, 10, '...'),
                ];
            }

            return $this->successresponse($manualauditList);

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('trainingController->manualauditlist->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('trainingController->manualauditlist->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }

    public function getauditDetail($trainingresultId)
    {
        try {
            $user = Auth::user();
            $auditdetailRecord = DB::table('training_results')
                ->join('topics', 'topics.id', '=', 'training_results.trainingtopic_id')
                ->leftJoin('courses', 'courses.id', 'topics.course_id')
                ->leftJoin('topictypes', 'topictypes.id', 'topics.type')
                ->join('trainee_trainings', 'trainee_trainings.id', 'training_results.trainingtrainee_id')
                ->join('user_trainee', 'user_trainee.trainee_id', '=' ,'trainee_trainings.trainee_id')
                ->join('trainees', 'trainees.id', '=', 'trainee_trainings.trainee_id')
                ->where('training_results.id', $trainingresultId)
                ->where('training_results.status', 'PENDDING')
                ->where('user_trainee.user_id', $user->id)
                ->select('trainees.name as trainee_name', 'topics.question', 'training_results.answer', 'training_results.id as result_id', 'courses.name as course_name', 'topictypes.name as type_name')
                ->first();
            if ($auditdetailRecord) {
                return $this->successresponse([
                    'trainingresultId' => $trainingresultId,
                    'trainee_name' => $auditdetailRecord->trainee_name,
                    'question' => $auditdetailRecord->question,
                    'answer' => $auditdetailRecord->answer,
                    'course_name' => $auditdetailRecord->course_name,
                    'type_name' => $auditdetailRecord->type_name                    
                ]);
            } else {
                return $this->failureresponse('Record not exists!');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('trainingController->getauditDetail->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('trainingController->getauditDetail->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }

    public function auditanawer(Request $request)
    {
        $this->validate($request, [
            'trainingresultId' => 'numeric|required',
            'result' => 'boolean|required'
        ]);

        try {
            $resultRecord = DB::table('training_results')
                ->where('training_results.id', $request->input('trainingresultId'))
                ->join('trainee_trainings', 'trainee_trainings.id', '=', 'training_results.trainingtrainee_id')
                ->select('training_results.status', 'trainee_trainings.trainee_id', 'training_results.trainingtopic_id')
                ->first();
            if ($resultRecord && $resultRecord->status == 'PENDDING') {
                DB::table('training_results')
                ->where('training_results.id', $request->input('trainingresultId'))
                ->update([
                    'status' => $request->input('result') ? 'CORRECT' : 'WRONG',
                    'updated_at' => Carbon::now()
                ]);

                $this->updatetraineetopicsummary($resultRecord->trainee_id, $resultRecord->trainingtopic_id, $request->input('result'));

                return $this->successresponse(['result' => $request->input('result')]);
            } else {
                return $this->failureresponse('No record or record status abnormal!');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('trainingController->auditanawer->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('trainingController->auditanawer->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }

    public function getmytraineesList()
    {
        try {
            $user = Auth::user();
            $traineeRecord = DB::table('trainees')
                ->join('user_trainee', 'user_trainee.trainee_id', '=' ,'trainees.id')
                ->where('user_trainee.user_id', $user->id)
                ->select('trainees.name', 'trainees.id', 'trainees.avatar')
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
            Log::error('trainingController->getmytraineesList->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('trainingController->getmytraineesList->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }

    public function getanswerDetail($resultId)
    {
        try {
            $user = Auth::user();
            $answerRecord = DB::table('training_results')
                ->leftJoin('topics', 'topics.id', '=', 'training_results.trainingtopic_id')
                ->leftJoin('courses', 'courses.id', '=', 'topics.course_id')
                ->leftJoin('topictypes', 'topictypes.id', '=', 'topics.type')
                ->leftJoin('trainee_trainings', 'trainee_trainings.id', '=', 'training_results.trainingtrainee_id')
                ->join('user_trainee', 'user_trainee.trainee_id', '=' ,'trainee_trainings.trainee_id')
                ->leftJoin('trainees', 'trainees.id', 'trainee_trainings.trainee_id')
                ->select('trainees.name as trainee_name', 'topics.question', 'topics.answer', 'courses.name as course_name', 'topictypes.name as topic_type', 'training_results.answer as trainee_answer', 'training_results.status', 'training_results.duration', 'trainee_trainings.trainee_id', 'topics.id as topic_id')
                ->where('training_results.id', $resultId)
                ->where('user_trainee.user_id', $user->id)
                ->first();
            if ($answerRecord) {
                $historyRecord = DB::table('training_results')
                    ->join('trainee_trainings', 'trainee_trainings.id', 'training_results.trainingtrainee_id')
                    ->join('trainnings', 'trainnings.id', 'trainee_trainings.training_id')
                    ->select('trainnings.title', 'training_results.created_at', 'training_results.status', 'training_results.answer')
                    ->where('trainee_trainings.trainee_id', $answerRecord->trainee_id)
                    ->where('training_results.trainingtopic_id', $answerRecord->topic_id)
                    ->where('training_results.id', '<>', $resultId)
                    ->orderBy('training_results.created_at', 'desc')
                    ->get();
                $historyList = [];
                foreach ($historyRecord as $his) {
                    $historyList[] = [
                        'title' => $his->title,
                        'created_at' => (new Carbon($his->created_at))->locale('zh_CN')->diffForHumans(Carbon::now()),
                        'status' => $his->status,
                        'answer' => $his->answer
                    ];
                }
                return $this->successresponse([
                    'trainee_name' => $answerRecord->trainee_name,
                    'question' => $answerRecord->question,
                    'answer' => $answerRecord->answer,
                    'course_name' => $answerRecord->course_name,
                    'topic_type' => $answerRecord->topic_type,
                    'trainee_answer' => $answerRecord->trainee_answer,
                    'status' => $answerRecord->status,
                    'duration' => $answerRecord->duration,
                    'topic_id' => $answerRecord->topic_id,
                    'history' => $historyList
                ]);
            } else {
                return $this->failureresponse('Record not exists');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('mytrainController->gettrainresult->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('mytrainController->gettrainresult->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }
}
