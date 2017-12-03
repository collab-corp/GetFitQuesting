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

Route::resource('news', 'NewsController');
Route::apiResource('quests', 'QuestController');
Route::name('me')->get('me', 'MeController');
Route::name('me.update')->patch('me', 'MeController@update');
Route::name('me.destroy')->delete('me', 'MeController@destroy');