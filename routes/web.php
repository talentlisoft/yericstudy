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

Route::get('rest/courses/list', 'Admin\coursesController@getcoursesList');
Route::post('rest/courses/save', 'Admin\topicsController@savetopic');
Route::get('rest/topics/summary', 'Admin\topicsController@summary');
Route::post('rest/topics/list', 'Admin\topicsController@topicsList');
Route::get('rest/topics/detail/{topicId}', 'Admin\topicsController@topicDetail');
Route::post('rest/trainings/list', 'Admin\trainingController@trainingsList');
Route::get('rest/trainees/list', 'Admin\TraineeController@traineeList');
Route::post('rest/trainees/topicslist', 'Admin\trainingController@gettopicsList');
Route::post('rest/training/add', 'Admin\trainingController@savetraining');
Route::post('rest/training/list', 'Admin\trainingController@trainingsList');

Route::post('resttrainee/mytrain/list', 'Trainee\mytrainController@mytrainlist');
Route::get('resttrainee/mytrain/detail/{traineetrainingId}', 'Trainee\mytrainController@gettraining');
Route::post('resttrainee/mytrain/submitanswer', 'Trainee\mytrainController@anawerquestion');
Route::get('resttrainee/mytrain/result/{traineetrainingId}', 'Trainee\mytrainController@gettrainresult');