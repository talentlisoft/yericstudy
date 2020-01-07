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

Route::get('topics/summary', 'Api\apitopicsController@summary');

Route::post('topics/list', 'Api\apitopicsController@topicsList');
Route::post('courses/save', 'Api\apitopicsController@savetopic');
Route::get('courses/list', 'Api\apicoursesController@getcoursesList');
Route::get('topics/detail/{topicId}', 'Api\apitopicsController@topicDetail');

Route::post('trainings/list', 'Api\apitrainingController@trainingsList');

Route::get('trainees/list', 'Api\apitraineeController@traineeList');
Route::post('trainees/topicslist', 'Api\apitrainingController@gettopicsList');
Route::post('training/add', 'Api\apitrainingController@savetraining');
Route::get('training/detail/{trainingId}', 'Api\apitrainingController@trainingDetail');
