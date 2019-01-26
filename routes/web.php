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

Route::get('/', 'HomeController@home');
Route::get('/home', 'HomeController@home')->name('home');
Route::get('/contact', 'HomeController@contact');
Route::get('/about', 'HomeController@about');

Route::get('/forum', 'ThreadsController@index');
Route::get('/forum/new', 'ThreadsController@create');
Route::post('/forum', 'ThreadsController@store');
Route::post('forum/{thread}/reply','ThreadReplyController@store');
Route::get('/forum/{thread}','ThreadsController@show');

Route::get('/dashboard', 'DashboardController@index');
Route::get('/dashboard/{game}','DashboardController@editGame');
Route::get('/contact', 'HomeController@contact');

Route::get('/games','GamesController@index');
Route::get('/game/schedule','GamesController@schedule');
Route::post('/game','GamesController@store');
Route::post('/game/{game}/toggleprivate','GamesController@togglePrivate');
Route::post('/game/{game}/invite/{user}','GamesController@invite');
Route::post('/game/{game}/leave','GamesController@leave');
Route::post('/game/{game}/join','GamesController@join');
Auth::routes();
