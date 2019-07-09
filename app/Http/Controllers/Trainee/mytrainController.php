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
                ->leftJoin('training_results', 'training_results.trainingtrainee_id', '=', 'trainee_trainings.id')
                ->select('trainee_trainings.id', 'trainnings.title', 'trainee_trainings.created_at', 'trainee_trainings.status', DB::raw('COUNT(training_topics.id) AS total_topics'), DB::raw('COUNT(training_results.id) AS finished_topics'))
                ->groupBy('trainee_trainings.id', 'trainnings.title', 'trainee_trainings.created_at', 'trainee_trainings.status')
                ->where(function ($query) use ($request) {
                    if ($request->input('scope') == 'PENDDING') {
                        $query->where('trainee_trainings.status', 0);
                    } else {
                        $query->where('trainee_trainings.status', 1);
                    }
                })
                ->paginate(10);
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
                ->where('training_results.trainingtrainee_id')->count();
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
                    ->leftJoin('training_results', function ($join) use ($traineetrainingId) {
                        $join->on('training_results.trainingtopic_id', '=', 'training_topics.topic_id');
                        $join->on('training_results.trainingtrainee_id', '=', DB::raw($traineetrainingId));
                    })
                    ->select('topics.question', 'topics.id', 'courses.name')
                    ->where('training_topics.training_id', $trainingRecord->training_id)
                    ->whereNull('training_results.id')
                    ->get();
                foreach ($topicsRecord as $key => $topic) {
                    $trainingData['pendding_topics'][] = [
                        'topic_id' => $topic->id,
                        'course_name' => $topic->name,
                        'question' => $topic->question,
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
                    ->select('answer')->where('id', $request->input('topic_id'))
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
                    $trainingresult->status = ($result ? 'CORRECT' : 'WRONG');
                    $trainingresult->duration = $request->input('duration');
                    $trainingresult->trainingtrainee_id = $request->input('traineetrainingId');

                    if ($trainingresult->save()) {
                        // Update trainee topic summary
                        if (DB::table('trainee_topics_summary')->where('trainee_id', $trainee->id)->where('topic_id', $request->input('topic_id'))->exists()) {
                            DB::table('trainee_topics_summary')
                                ->where('trainee_id', $trainee->id)
                                ->where('topic_id', $request->input('topic_id'))
                                ->increment($result?'corrent_count':'fail_count', 1, [
                                    'recent_failed' => ($result==false) ? true : false,
                                    'updated_at' => Carbon::now()
                                ]);
                        } else {
                            DB::table('trainee_topics_summary')->insert([
                                'trainee_id' => $trainee->id,
                                'topic_id' => $request->input('topic_id'),
                                'corrent_count' => ($result? 1 : 0),
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
}
