<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\Trainee;
use App\Models\TrainingResult as TrainingResult;
use App\Models\TrainingTrainees as TrainingTrainees;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class mytrainController extends Controller
{
    public function __construct()
    {
        $this->middleware('traineeauth');
    }

    public function mytrainlist(Request $request, Trainee $trainee)
    {
        $this->validate($request, [
            'scope' => 'required',
        ]);

        try {
            $mytrainRecord = DB::table('trainee_trainings')
                ->join('trainnings', 'trainnings.id', '=', 'trainee_trainings.training_id')
                ->leftJoin('training_topics', 'training_topics.training_id', '=', 'trainee_trainings.training_id')
                ->leftJoin('training_results', function($join) {
                    $join->on('training_results.trainingtrainee_id', '=', 'trainee_trainings.id');
                    $join->on('training_results.trainingtopic_id', '=', 'training_topics.topic_id');
                })
                ->select('trainee_trainings.id', 'trainnings.title', 'trainnings.created_at', 'trainee_trainings.status', DB::raw('COUNT(training_topics.id) AS total_topics'), DB::raw('SUM(IF(training_results.id IS NULL, 0, 1)) AS finished_topics'))
                ->groupBy('trainee_trainings.id', 'trainnings.title', 'trainnings.created_at', 'trainee_trainings.status')
                ->where(function ($query) use ($request) {
                    if ($request->input('scope') == 'PENDDING') {
                        $query->where('trainee_trainings.status', 0);
                    } else {
                        $query->where('trainee_trainings.status', 1);
                    }
                })
                ->where('trainee_trainings.trainee_id', $trainee->id)
                ->orderBy('trainnings.created_at', 'desc')
                ->paginate(12);
            $mytrainList = [];
            foreach ($mytrainRecord as $tr) {
                $mytrainList[] = [
                    'id' => $tr->id,
                    'title' => $tr->title,
                    'created_at' => (new Carbon($tr->created_at))->locale('zh_CN')->diffForHumans(Carbon::now()),
                    'total_topics' => $tr->total_topics,
                    'status' => $tr->status,
                    'finished_topics' => $tr->finished_topics,
                ];
            }
            return $this->successresponse(['list' => $mytrainList, 'total' => $mytrainRecord->total()]);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('mytrainController->mytrainlist->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('mytrainController->mytrainlist->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }

    public function gettraining($traineetrainingId, Trainee $trainee)
    {
        try {
            $trainingRecord = DB::table('trainee_trainings')
                ->join('trainnings', 'trainnings.id', '=', 'trainee_trainings.training_id')
                ->select('trainnings.title', 'trainee_trainings.created_at', 'trainee_trainings.training_id')
                ->where('trainee_trainings.id', $traineetrainingId)
                ->where('trainee_trainings.trainee_id', $trainee->id)
                ->first();
            $finishedtopics_count = DB::table('training_results')
                ->where('training_results.trainingtrainee_id', $traineetrainingId)->count();
            $trainingData = [
                'id' => $traineetrainingId,
                'title' => $trainingRecord->title,
                'created_at' => (new Carbon($trainingRecord->created_at))->locale('zh_CN')->diffForHumans(Carbon::now()),
                'finished_count' => $finishedtopics_count,
                'pendding_topics' => [],
            ];
            if ($trainingRecord) {
                $topicsRecord = DB::table('training_topics')
                    ->join('topics', 'topics.id', '=', 'training_topics.topic_id')
                    ->join('courses', 'courses.id', '=', 'topics.course_id')
                    ->leftJoin('topictypes', 'topictypes.id', '=', 'topics.type')
                    ->leftJoin('training_results', function ($join) use ($traineetrainingId) {
                        $join->on('training_results.trainingtopic_id', '=', 'training_topics.topic_id');
                        $join->on('training_results.trainingtrainee_id', '=', DB::raw($traineetrainingId));
                    })
                    ->select('topics.question', 'topics.id', 'courses.name', 'topictypes.name as topic_type')
                    ->where('training_topics.training_id', $trainingRecord->training_id)
                    ->whereNull('training_results.id')
                    ->get();
                foreach ($topicsRecord as $key => $topic) {
                    $trainingData['pendding_topics'][] = [
                        'topic_id' => $topic->id,
                        'course_name' => $topic->name,
                        'question' => $topic->question,
                        'topic_type' =>$topic->topic_type
                    ];
                }

                return $this->successresponse($trainingData);
            } else {
                return $this->failureresponse('Record not exists!');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('mytrainController->gettraining->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('mytrainController->gettraining->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }

    public function anawerquestion(Request $request, Trainee $trainee)
    {
        $this->validate($request, [
            'topic_id' => 'numeric|required',
            'answer' => 'required|max:50',
            'duration' => 'required',
            'traineetrainingId' => 'required|numeric',
        ]);

        try {
            $trainingtrainee = TrainingTrainees::where('trainee_trainings.id', $request->input('traineetrainingId'))
                ->where('trainee_trainings.trainee_id', $trainee->id)
                ->first();
            if ($trainingtrainee) {
                $topicsRecord = DB::table('topics')
                    ->select('answer', 'manualverify')->where('id', $request->input('topic_id'))
                    ->first();
                if ($topicsRecord) {
                    $result = false;
                    $answerList = explode('|', $topicsRecord->answer);
                    foreach ($answerList as $answer) {
                        if (trim(strtolower($request->input('answer'))) == trim(strtolower($answer))) {
                            $result = true;
                            break;
                        }
                    }
                    $trainingresult = TrainingResult::where('trainingtopic_id', $request->input('topic_id'))->where('trainingtrainee_id', $request->input('traineetrainingId'))->first();
                    if (!$trainingresult) {
                        $trainingresult = new TrainingResult;
                    }
                    $trainingresult->trainingtopic_id = $request->input('topic_id');
                    $trainingresult->answer = $request->input('answer');
                    $trainingresult->status = ($topicsRecord->manualverify ? 'PENDDING' : ($result ? 'CORRECT' : 'WRONG'));
                    $trainingresult->duration = $request->input('duration');
                    $trainingresult->trainingtrainee_id = $request->input('traineetrainingId');

                    if ($trainingresult->save()) {
                        // Update trainee topic summary
                        if (DB::table('trainee_topics_summary')->where('trainee_id', $trainee->id)->where('topic_id', $request->input('topic_id'))->exists()) {
                            DB::table('trainee_topics_summary')
                                ->where('trainee_id', $trainee->id)
                                ->where('topic_id', $request->input('topic_id'))
                                ->increment($result?'correct_count':'fail_count', 1, [
                                    'recent_failed' => ($result==false) ? true : false,
                                    'updated_at' => Carbon::now()
                                ]);
                        } else {
                            DB::table('trainee_topics_summary')->insert([
                                'trainee_id' => $trainee->id,
                                'topic_id' => $request->input('topic_id'),
                                'correct_count' => ($result? 1 : 0),
                                'fail_count' => ($result==false ? 1 : 0),
                                'recent_failed' => ($result==false) ? true : false,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ]);
                        }
                        // Check if training finished
                        $isFinished = !DB::table('training_topics')
                            ->leftJoin('training_results', function ($join) use ($request) {
                                $join->on('training_results.trainingtopic_id', '=', 'training_topics.topic_id');
                                $join->on('training_results.trainingtrainee_id', '=', DB::raw($request->input('traineetrainingId')));
                            })
                            ->where('training_topics.training_id', $trainingtrainee->training_id)
                            ->whereNull('training_results.id')
                            ->exists();
                        if ($isFinished) {
                            // Make trainee train closeed
                            $trainingtrainee->status = 1;
                            $trainingtrainee->save();
                        }
                        return $this->successresponse(['isFinished' => $isFinished]);
                    } else {
                        return $this->failureresponse('Can not save training result');
                    }
                } else {
                    return $this->failureresponse('Topic not exist!');
                }
            } else {
                return $this->failureresponse('Can not find training record');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('mytrainController->anawerquestion->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('mytrainController->anawerquestion->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }

    public function gettrainresult($traineetrainingId, Trainee $trainee)
    {
        try {
            $trainingRecord = DB::table('trainee_trainings')
                ->join('trainnings', 'trainnings.id', '=', 'trainee_trainings.training_id')
                ->select('trainnings.title', 'trainee_trainings.created_at', 'trainee_trainings.training_id')
                ->where('trainee_trainings.id', $traineetrainingId)
                ->where('trainee_trainings.trainee_id', $trainee->id)
                ->first();
            if ($trainingRecord) {
                $trainingresult = DB::table('training_results')
                    ->join('topics', 'topics.id', '=', 'training_results.trainingtopic_id')
                    ->leftJoin('trainee_trainings', 'trainee_trainings.id', 'training_results.trainingtrainee_id')
                    ->leftJoin('trainee_topics_summary', function($join) {
                        $join->on('trainee_topics_summary.topic_id', '=', 'topics.id');
                        $join->on('trainee_topics_summary.trainee_id', '=', 'trainee_trainings.trainee_id');
                    })
                    ->select('topics.question', 'training_results.answer', 'training_results.status', 'training_results.duration', 'training_results.id', 'trainee_topics_summary.correct_count', 'trainee_topics_summary.fail_count')
                    ->where('training_results.trainingtrainee_id', $traineetrainingId)
                    ->get();

                $resultList = [];
                $correctCount = 0;
                foreach ($trainingresult as $key => $result) {
                    $resultList[] = [
                        'question' => mb_strimwidth($result->question, 0, 10, '...'),
                        'answer' => $result->answer,
                        'status' => $result->status,
                        'duration' => $result->duration,
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
                    'score' => round(($correctCount / count($trainingresult)) * 100)
                ]);
            } else {
                return $this->failureresponse('Can not find such training');
            }

            
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('mytrainController->gettrainresult->QueryException异常' . $e->getMessage());
            return $this->failureresponse('数据库查询出错了');
        } catch (Exception $e) {
            Log::error('mytrainController->gettrainresult->Exception' . $e->getMessage());
            return $this->failureresponse('操作失败.');
        }
    }

    public function getanswerDetail($resultId, Trainee $trainee)
    {
        try {
            $answerRecord = DB::table('training_results')
                ->leftJoin('topics', 'topics.id', '=', 'training_results.trainingtopic_id')
                ->leftJoin('courses', 'courses.id', '=', 'topics.course_id')
                ->leftJoin('topictypes', 'topictypes.id', '=', 'topics.type')
                ->leftJoin('trainee_trainings', 'trainee_trainings.id', '=', 'training_results.trainingtrainee_id')
                ->leftJoin('trainees', 'trainees.id', 'trainee_trainings.trainee_id')
                ->select('trainees.name as trainee_name', 'topics.question', 'topics.answer', 'courses.name as course_name', 'topictypes.name as topic_type', 'training_results.answer as trainee_answer', 'training_results.status', 'training_results.duration')
                ->where('training_results.id', $resultId)
                ->where('trainee_trainings.trainee_id', $trainee->id)
                ->first();
            if ($answerRecord) {
                return $this->successresponse([
                    'trainee_name' => $answerRecord->trainee_name,
                    'question' => $answerRecord->question,
                    'answer' => $answerRecord->answer,
                    'course_name' => $answerRecord->course_name,
                    'topic_type' => $answerRecord->topic_type,
                    'trainee_answer' => $answerRecord->trainee_answer,
                    'status' => $answerRecord->status,
                    'duration' => $answerRecord->duration
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
