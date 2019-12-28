<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Trainee\loginController@showloginpage');

Route::post('traineeauth/loing', 'Trainee\loginController@login')->name('traineelogin');
Route::get('traineeauth/logout', 'Trainee\loginController@logout');

Auth::routes();

Route::get('/adminpages/{pagename}', 'Admin\commonController@adminpages');
Route::get('/traineepages/{pagename}', 'Trainee\commonController@traineepages');

Route::get('admin/{module}/{method?}/{optional?}/{optional2?}/{optional3?}', 'Admin\commonController@adminmain');
Route::get('trainee/{module}/{method?}/{optional?}/{optional2?}/{optional3?}', 'Trainee\commonController@showtraineemainpage');

Route::get('rest/training/resultdetail/{resultId}', 'Admin\trainingController@getanswerDetail');
Route::get('rest/common/permission', 'Admin\commonController@getusrpermission');
Route::get('rest/courses/list', 'Web\webcoursesController@getcoursesList');
Route::post('rest/courses/save', 'Web\webtopicsController@savetopic');
Route::get('rest/topics/summary', 'Web\webtopicsController@summary');
Route::post('rest/topics/list', 'Web\webtopicsController@topicsList');
Route::get('rest/topics/detail/{topicId}', 'Web\webtopicsController@topicDetail');
Route::post('rest/trainings/list', 'Admin\trainingController@trainingsList');
Route::get('rest/trainees/list', 'Admin\TraineeController@traineeList');
Route::get('rest/trainees/mylist', 'Admin\trainingController@getmytraineesList');
Route::post('rest/trainees/save', 'Admin\TraineeController@savetrainee');
Route::post('rest/trainees/topicslist', 'Admin\trainingController@gettopicsList');
Route::post('rest/training/add', 'Admin\trainingController@savetraining');
Route::post('rest/training/list', 'Admin\trainingController@trainingsList');
Route::get('rest/training/detail/{trainingId}', 'Admin\trainingController@trainingDetail');
Route::get('rest/training/result/{traineetrainingId}', 'Admin\trainingController@trainingResult');
Route::get('rest/manualaudit/list', 'Admin\trainingController@manualauditlist');
Route::get('rest/manualaudit/detail/{trainingresultId}', 'Admin\trainingController@getauditDetail');
Route::post('rest/manualaudit/auditanswer', 'Admin\trainingController@auditanawer');
Route::get('rest/users/list', 'Admin\usersController@userslist');
Route::post('rest/users/save', 'Admin\usersController@saveuser');
Route::get('rest/users/detail/{userId}', 'Admin\usersController@userDetail');

Route::post('resttrainee/mytrain/list', 'Trainee\mytrainController@mytrainlist');
Route::get('resttrainee/mytrain/detail/{traineetrainingId}', 'Trainee\mytrainController@gettraining');
Route::post('resttrainee/mytrain/submitanswer', 'Trainee\mytrainController@anawerquestion');
Route::get('resttrainee/mytrain/result/{traineetrainingId}', 'Trainee\mytrainController@gettrainresult');
Route::get('resttrainee/mytrain/result/detail/{resultId}', 'Trainee\mytrainController@getanswerDetail');