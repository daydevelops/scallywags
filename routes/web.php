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

Route::get('/forum', 'ThreadsController@index')->name('forum');
Route::get('/forum/new', 'ThreadsController@create');
Route::post('/forum', 'ThreadsController@store');
Route::post('/forum/{category}/{thread}/subscribe','ThreadSubscriptionController@store');
Route::delete('/forum/{category}/{thread}/unsubscribe','ThreadSubscriptionController@destroy');
Route::post('/forum/{category}/{thread}/reply','ThreadReplyController@store');
Route::get('/forum/{category}/{thread}','ThreadsController@show');
Route::get('/forum/{category}','ThreadsController@index');
Route::get('/forum/{category}/{thread}/replies','ThreadReplyController@show');
Route::delete('/forum/reply/{reply}','ThreadReplyController@destroy');
Route::delete('/forum/{category}/{thread}', 'ThreadsController@destroy');
Route::patch('/forum/reply/{reply}','ThreadReplyController@update');

Route::get('/profile/{user}','ProfileController@show');

Route::post('/favourite/thread/{thread}','FavouriteController@storeThread');
Route::post('/favourite/reply/{reply}','FavouriteController@storeReply');
Route::delete('/favourite/thread/{thread}','FavouriteController@destroyThread');
Route::delete('/favourite/reply/{reply}','FavouriteController@destroyReply');

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

Route::get('/notifications','UserNotificationController@index');
Route::delete('/notifications/all','UserNotificationController@destroyAll');
Route::delete('/notifications/{notification}','UserNotificationController@destroy');

Route::get('/api/users','Api\UsersController@index');
