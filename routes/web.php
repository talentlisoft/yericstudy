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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/adminpages/{pagename}', 'admin\commonController@adminpages');

Route::get('admin/{module}/{method?}/{optional?}/{optional2?}/{optional3?}', 'admin\commonController@adminmain');
