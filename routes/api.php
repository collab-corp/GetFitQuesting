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
Route::post('register', 'Auth\RegisterController@__invoke');
Route::post('login', 'Auth\LoginController@__invoke');
Route::post('logout', 'Auth\LogoutController@__invoke');

Route::resource('news', 'NewsController');
Route::apiResource('teams', 'TeamController');
Route::apiResource('quests', 'QuestController');
Route::apiResource('stories', 'StoryController');
Route::name('story.enroll')->post('stories/{story}/enroll', 'Story\EnrollStoryController@__invoke');
Route::name('story.leave')->delete('stories/{story}/leave', 'Story\LeaveStoryController@__invoke');

Route::apiResource('guilds', 'GuildController');
Route::name('guild.leave')->delete('guilds/{guild}/leave', 'Guild\LeaveGuildController@__invoke');
Route::name('guild.teams')->get('guild/{guild}/teams', 'Guild\GuildTeamsController@index');

Route::name('me')->get('me', 'MeController');
Route::name('me.update')->patch('me', 'MeController@update');
Route::name('me.destroy')->delete('me', 'MeController@destroy');
