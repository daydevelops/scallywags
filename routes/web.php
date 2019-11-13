<?php
use App\Events\UserSentMessage;
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

///// FORUM /////
Route::get('/', 'ThreadsController@index');
Route::get('/forum', 'ThreadsController@index')->name('forum');
Route::get('/forum/new', 'ThreadsController@create')->middleware('verified');
Route::get('/forum/{category}/{thread}','ThreadsController@show');
Route::get('/forum/{category}','ThreadsController@index');
Route::get('/forum/{category}/{thread}/replies','ThreadReplyController@show'); //TODO: restrict to admin

Route::post('/forum', 'ThreadsController@store')->middleware('verified');
Route::post('/forum/{category}/{thread}/subscribe','ThreadSubscriptionController@store');
Route::post('/forum/{category}/{thread}/reply','ThreadReplyController@store');
Route::post('/forum/{category}/{thread}/lock','ThreadsController@lock')->middleware('admin');
Route::post('/forum/{category}/{thread}/unlock','ThreadsController@unlock')->middleware('admin');
Route::post('/forum/{category}/{thread}/pin','ThreadsController@pin')->middleware('admin');
Route::post('/forum/{category}/{thread}/unpin','ThreadsController@unpin')->middleware('admin');
Route::post('/forum/reply/{reply}/best','BestReplyController@store');

Route::delete('/forum/reply/{reply}','ThreadReplyController@destroy');
Route::delete('/forum/{category}/{thread}', 'ThreadsController@destroy');
Route::delete('/forum/{category}/{thread}/unsubscribe','ThreadSubscriptionController@destroy');

Route::patch('/forum/reply/{reply}','ThreadReplyController@update');
Route::patch('/forum/{category}/{thread}','ThreadsController@update');
/////////////////

///// Favourites /////
Route::get('/favourites','FavouriteController@index');

Route::post('/favourite/thread/{thread}','FavouriteController@storeThread');
Route::post('/favourite/reply/{reply}','FavouriteController@storeReply');

Route::delete('/favourite/thread/{thread}','FavouriteController@destroyThread');
Route::delete('/favourite/reply/{reply}','FavouriteController@destroyReply');
//////////////////////

///// PROFILE /////
Route::post('/profile/avatar','Api\AvatarController@store');
Route::get('/profile/{user}','ProfileController@show');

Route::get('/dashboard', 'DashboardController@index');

///// MESSAGING /////
Route::get('/conversations','ConversationController@index');
/////////////////////


Auth::routes(['verify' => true]);

Route::get('/notifications','UserNotificationController@index');
Route::delete('/notifications/all','UserNotificationController@destroyAll');
Route::delete('/notifications/{notification}','UserNotificationController@destroy');

Route::get('/api/users','Api\UsersController@index');



Route::get('/chat', function() {
    return view('home');
});
Route::get('/msg', function() {
    UserSentMessage::dispatch("hello");
});