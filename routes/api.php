<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('auth/login', 'Api\authController@login');
Route::get('auth/userinfo', 'Api\authController@userinfo');
Route::get('auth/captcha', 'Api\authController@getcaptcha');
Route::post('auth/wechatlogin', 'Api\authController@wechartlogin');

Route::get('topics/summary', 'Api\apitopicsController@summary');

Route::post('topics/list', 'Api\apitopicsController@topicsList');
Route::post('courses/save', 'Api\apitopicsController@savetopic');
Route::get('courses/list', 'Api\apicoursesController@getcoursesList');
Route::get('topics/detail/{topicId}', 'Api\apitopicsController@topicDetail');

Route::post('trainings/list', 'Api\apitrainingController@trainingsList');
Route::post('trainings/getradomtopics', 'Api\apitrainingController@getradomtopics');

Route::get('trainees/list', 'Api\apitraineeController@traineeList');
Route::post('trainees/topicslist', 'Api\apitrainingController@gettopicsList');
Route::post('training/add', 'Api\apitrainingController@savetraining');
Route::get('training/detail/{trainingId}', 'Api\apitrainingController@trainingDetail');
Route::get('training/result/{traineetrainingId}', 'Api\apitrainingController@trainingResult');
Route::get('training/resultdetail/{resultId}', 'Api\apitrainingController@getanswerDetail');
Route::get('training/result/changejudgement/{resultId}', 'Api\apitrainingController@changejudgement');
Route::get('manualaudit/list', 'Api\apitrainingController@manualauditlist');
Route::get('manualaudit/detail/{trainingresultId}', 'Api\apitrainingController@getauditDetail');
Route::post('manualaudit/auditanswer', 'Api\apitrainingController@auditanawer');
