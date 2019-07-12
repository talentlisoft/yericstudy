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

class trainingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function trainingsList(Request $request)
    {
        $this->validate($request, [
            'searchcontent' => 'nullable',
        ]);
        try {
            $trainingsRecord = DB::table('trainnings')
                ->select('trainnings.title', 'trainnings.created_at', 'trainnings.id', 
                DB::raw('COUNT(trainee_trainings.id) AS trainee_count'),
                DB::raw('SUM(IF(trainee_trainings.status = 1, 1, 0)) AS finished_count')
                )
                ->leftJoin('trainee_trainings', 'trainee_trainings.training_id', '=', 'trainnings.id')
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
            switch ($request->input('mode')) {
                case 'RADOM':
                    $topicRecord = DB::table('topics')
                        ->leftJoin('courses', 'courses.id', 'topics.course_id')
                        ->leftJoin('topictypes', 'topictypes.id', '=', 'topics.type')
                        ->select('topics.id', 'topics.question', 'topics.updated_at', 'topics.level', 'topics.grade', 'courses.name', 'topictypes.name AS topic_type')
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
                        ->inRandomOrder()
                        ->paginate(20);
                    break;
                case 'RECENT':
                    $topicRecord = DB::table('trainee_topics_summary')
                        ->join('topics', 'topics.id', 'trainee_topics_summary.topic_id')
                        ->leftJoin('topictypes', 'topictypes.id', '=', 'topics.type')
                        ->leftJoin('courses', 'courses.id', 'topics.course_id')
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
                        ->select('topics.id', 'topics.question', 'topics.updated_at', 'topics.level', 'topics.grade', 'courses.name', 'topictypes.name AS topic_type')
                        ->distinct()
                        ->paginate(20);
                    break;
                case 'EVER':
                    $topicRecord = DB::table('trainee_topics_summary')
                        ->join('topics', 'topics.id', 'trainee_topics_summary.topic_id')
                        ->leftJoin('topictypes', 'topictypes.id', '=', 'topics.type')
                        ->leftJoin('courses', 'courses.id', 'topics.course_id')
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
                        ->orderBy(DB::raw('(SUM( trainee_topics_summary.correct_count ) - SUM( trainee_topics_summary.fail_count ))'), 'desc')
                        ->groupBy('topics.id', 'topics.question', 'topics.updated_at', 'topics.level', 'topics.grade', 'courses.name', 'topictypes.name')
                        ->paginate(20);
                    break;
                case 'FREQUENCY':
                    $topicRecord = DB::table('topics')
                        ->leftJoin('topictypes', 'topictypes.id', '=', 'topics.type')
                        ->leftJoin('training_results', 'training_results.trainingtopic_id', '=', 'topics.id')
                        ->leftJoin('courses', 'courses.id', '=','topics.course_id')
                        ->leftJoin('trainee_trainings', 'trainee_trainings.id', '=', 'training_results.trainingtrainee_id')
                        ->select('topics.id', 'topics.question', 'topics.updated_at', 'topics.level', 'topics.grade', 'courses.name', 'topictypes.name AS topic_type', DB::raw('COUNT(training_results.id)'))
                        ->where(function($query) use ($request) {
                            if (!empty($request->input('trainees'))) {
                                $query->whereIn('trainee_trainings.trainee_id', $request->input('trainees'));
                                $query->orWhereNull('training_results.id');
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
                        ->groupBy('topics.id', 'topics.question', 'topics.updated_at', 'topics.level', 'topics.grade', 'courses.name', 'topictypes.name')
                        ->orderBy(DB::raw('COUNT(training_results.id)'))
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
                    'level' => $to->level,
                    'grade' => $to->grade,
                    'course_name' => $to->name,
                    'topic_type' => $to->topic_type,
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
            $traineeRecord = DB::table('trainee_trainings')
                ->join('trainees', 'trainees.id', '=', 'trainee_trainings.trainee_id')
                ->select('trainees.name', 'trainee_trainings.id', 
                    DB::raw('(SELECT COUNT(*) FROM training_topics WHERE training_topics.training_id = trainee_trainings.training_id) AS total_topics'), 
                    DB::raw('(SELECT COUNT(*) FROM training_results WHERE training_results.trainingtrainee_id = trainee_trainings.id) AS finished_topics'), 
                    'trainee_trainings.status')
                ->where('trainee_trainings.training_id', $trainingId)
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
            $trainingRecord = DB::table('trainee_trainings')
                ->join('trainnings', 'trainnings.id', '=', 'trainee_trainings.training_id')
                ->select('trainnings.title', 'trainee_trainings.created_at', 'trainee_trainings.training_id', 'trainee_trainings.status')
                ->where('trainee_trainings.id', $traineetrainingId)
                ->first();
            if ($trainingRecord) {
                $trainingresult = DB::table('training_topics')
                    ->join('topics', 'topics.id', '=', 'training_topics.topic_id')
                    ->leftJoin('topictypes', 'topictypes.id', '=', 'topics.type')
                    ->leftJoin('training_results', function($join) use ( $traineetrainingId) {
                        $join->on('training_results.trainingtrainee_id', '=', DB::raw($traineetrainingId));
                        $join->on('training_results.trainingtopic_id', '=', 'training_topics.topic_id');
                    })
                    ->select('topics.question', 'training_results.answer', 'training_results.status', 'training_results.duration', 'topictypes.name as topic_type')
                    ->where('training_topics.training_id', $trainingRecord->training_id)
                    ->get();

                $resultList = [];
                $correctCount = 0;
                foreach ($trainingresult as $key => $result) {
                    $resultList[] = [
                        'question' => mb_strimwidth($result->question, 0, 10, '...'),
                        'answer' => $result->answer ?? '--',
                        'status' => $result->status? ($result->status == 'CORRECT' ? true : false) : '--',
                        'duration' => $result->duration ?? '--',
                        'topic_type' => $result->topic_type,
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
}
